<?php

namespace App\Http\Controllers;

use App\Models\DataGulma;
use App\Models\ImportLog;
use App\Models\MapPublication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GulmaController extends Controller
{
    private function getLatestPublishedImportId()
    {
        $publication = MapPublication::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->first();
        
        return $publication ? $publication->import_log_id : null;
    }

    /**
     * Deduplicate data for MAP display (kategori, neto, hasil)
     * BUT keep raw total_tk without deduplication
     */
    private function deduplicateDataForMap($data)
    {
        $deduped = [];
        $kategoriValue = ['bersih' => 1, 'ringan' => 2, 'sedang' => 3, 'berat' => 4];
        
        foreach ($data as $record) {
            $normalizedSeksi = strtolower(trim($record->seksi));
            
            if (!isset($deduped[$normalizedSeksi])) {
                $deduped[$normalizedSeksi] = (object)[
                    'wilayah_id' => $record->wilayah_id,
                    'seksi' => $record->seksi,
                    'kategori' => strtolower($record->kategori ?? ''),
                    'neto' => (float)$record->neto,
                    'hasil' => (float)$record->hasil,
                    'umur_tanaman' => (float)$record->umur_tanaman,
                    'kategoriValue' => $kategoriValue[strtolower($record->kategori ?? 'berat')] ?? 5,
                    'count' => 1
                ];
            } else {
                $existing = $deduped[$normalizedSeksi];
                $dataValue = $kategoriValue[strtolower($record->kategori ?? 'berat')] ?? 5;
                
                // Keep BEST kategori
                if ($dataValue < $existing->kategoriValue) {
                    $existing->kategori = strtolower($record->kategori ?? '');
                    $existing->kategoriValue = $dataValue;
                }
                
                // SUM values
                $existing->neto += (float)$record->neto;
                $existing->hasil += (float)$record->hasil;
                
                // Average umur tanaman
                $existing->umur_tanaman = (($existing->umur_tanaman * $existing->count) + (float)$record->umur_tanaman) / ($existing->count + 1);
                $existing->count++;
            }
        }
        
        return collect(array_values($deduped));
    }

    /**
     * API: Summary statistik per wilayah (FIXED - NO deduplication for total_tk)
     */
    public function getStatistikSummary(Request $request)
    {
        try {
            \Log::info("📊 === getStatistikSummary called ===");
            
            // Get latest published import_log_id
            $latestImportId = $this->getLatestPublishedImportId();
            
            if (!$latestImportId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang dipublikasikan',
                    'data' => []
                ]);
            }
            
            \Log::info("Using latest published import_id: {$latestImportId}");
            
            // Get ALL data from latest published import
            $query = DataGulma::where('import_log_id', $latestImportId);
            
            $allData = $query->get();
            \Log::info("Total raw records: " . $allData->count());
            
            // Group by wilayah
            $wilayahGroups = $allData->groupBy('wilayah_id');
            
            $summaryData = [];
            
            foreach ($wilayahGroups as $wilayahId => $records) {
                // PENTING: Deduplicate untuk peta (kategori, neto, hasil)
                $dedupedForMap = $this->deduplicateDataForMap($records);
                
                // PENTING: Total TK langsung dari CSV (NO deduplication)
                $rawTotalTk = $records->sum(function($record) {
                    return (float)$record->total_tk;
                });
                
                \Log::info("Wilayah {$wilayahId}: Raw Total TK from CSV = {$rawTotalTk}");
                
                $totalNeto = $dedupedForMap->sum('neto');
                $totalHasil = $dedupedForMap->sum('hasil');
                $avgHasil = $dedupedForMap->avg('hasil');
                $avgUmur = $dedupedForMap->avg('umur_tanaman');
                
                $summaryData[] = [
                    'wilayah_id' => $wilayahId,
                    'total_features' => $dedupedForMap->count(),
                    'total_neto' => round($totalNeto, 2),
                    'total_hasil' => round($totalHasil, 2),
                    'avg_hasil' => round($avgHasil, 2),
                    'avg_umur' => round($avgUmur, 1),
                    'total_tenaga_kerja' => (int)round($rawTotalTk), // DIRECT from CSV, rounded
                    'raw_count' => $records->count() // For debugging
                ];
                
                \Log::info("Wilayah {$wilayahId}: {$records->count()} raw → {$dedupedForMap->count()} deduped features, Total TK: {$rawTotalTk} → " . round($rawTotalTk));
            }
            
            // Sort by wilayah_id
            usort($summaryData, function($a, $b) {
                return $a['wilayah_id'] - $b['wilayah_id'];
            });

            return response()->json([
                'success' => true,
                'data' => $summaryData,
                'import_log_id' => $latestImportId,
                'filters' => [
                    'using_latest_published' => true
                ]
            ]);
            
        } catch (\Throwable $e) {
            \Log::error("Error in getStatistikSummary: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Query statistik gagal: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * API: Ranking wilayah (FIXED - NO deduplication for total_tk)
     */
    public function getStatistikRanking(Request $request)
    {
        try {
            \Log::info("🏆 === getStatistikRanking called ===");
            
            // Get latest published import_log_id
            $latestImportId = $this->getLatestPublishedImportId();
            
            if (!$latestImportId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang dipublikasikan',
                    'data' => []
                ]);
            }
            
            \Log::info("Using latest published import_id: {$latestImportId}");
            
            // Get ALL data from latest published import
            $allData = DataGulma::where('import_log_id', $latestImportId)->get();
            
            // Group by wilayah
            $wilayahGroups = $allData->groupBy('wilayah_id');
            
            $rankingData = [];
            
            foreach ($wilayahGroups as $wilayahId => $records) {
                // Deduplicate untuk peta
                $dedupedForMap = $this->deduplicateDataForMap($records);
                
                // Total TK langsung dari CSV (NO deduplication)
                $rawTotalTk = $records->sum(function($record) {
                    return (float)$record->total_tk;
                });
                
                $totalHasil = $dedupedForMap->sum('hasil');
                $avgHasil = $dedupedForMap->avg('hasil');
                $totalNeto = $dedupedForMap->sum('neto');
                
                $rankingData[] = [
                    'wilayah_id' => $wilayahId,
                    'total_hasil' => round($totalHasil, 2),
                    'avg_hasil' => round($avgHasil, 2),
                    'jumlah_features' => $dedupedForMap->count(),
                    'total_neto' => round($totalNeto, 2),
                    'total_tenaga_kerja' => (int)round($rawTotalTk) // DIRECT from CSV
                ];
            }
            
            // Sort by total_hasil DESC
            usort($rankingData, function($a, $b) {
                return $b['total_hasil'] <=> $a['total_hasil'];
            });

            return response()->json([
                'success' => true,
                'data' => $rankingData,
                'import_log_id' => $latestImportId
            ]);
            
        } catch (\Throwable $e) {
            \Log::error("Error in getStatistikRanking: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil ranking: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * API: Produktivitas (FIXED - use deduped data for map display)
     */
    public function getStatistikProductivity(Request $request)
    {
        try {
            \Log::info("📈 === getStatistikProductivity called ===");
            
            // Get latest published import_log_id
            $latestImportId = $this->getLatestPublishedImportId();
            
            if (!$latestImportId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang dipublikasikan',
                    'data' => []
                ]);
            }
            
            // Get ALL data from latest published import
            $allData = DataGulma::where('import_log_id', $latestImportId)->get();
            
            // Deduplicate untuk peta (hasil analysis)
            $dedupedRecords = $this->deduplicateDataForMap($allData);
            
            // Classify by productivity
            $tinggi = $dedupedRecords->filter(function($record) {
                return $record->hasil > 9;
            });
            
            $sedang = $dedupedRecords->filter(function($record) {
                return $record->hasil >= 8 && $record->hasil <= 9;
            });
            
            $rendah = $dedupedRecords->filter(function($record) {
                return $record->hasil < 8;
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'tinggi' => [
                        'count' => $tinggi->count(),
                        'avg' => round($tinggi->avg('hasil') ?? 0, 2)
                    ],
                    'sedang' => [
                        'count' => $sedang->count(),
                        'avg' => round($sedang->avg('hasil') ?? 0, 2)
                    ],
                    'rendah' => [
                        'count' => $rendah->count(),
                        'avg' => round($rendah->avg('hasil') ?? 0, 2)
                    ],
                ],
                'import_log_id' => $latestImportId
            ]);
            
        } catch (\Throwable $e) {
            \Log::error("Error in getStatistikProductivity: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Analisis produktivitas gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Perbandingan tahunan (uses published data per year)
     */
    public function getYearlyComparison()
    {
        try {
            \Log::info("📅 === getYearlyComparison called ===");
            
            // Get all published data grouped by year
            $publications = MapPublication::where('status', 'published')
                ->orderBy('tahun')
                ->get()
                ->groupBy('tahun');
            
            $yearlyData = [];
            
            foreach ($publications as $tahun => $pubs) {
                // Get latest publication for this year
                $latestPub = $pubs->sortByDesc('published_at')->first();
                
                if (!$latestPub) continue;
                
                // Get data for this publication
                $data = DataGulma::where('import_log_id', $latestPub->import_log_id)->get();
                
                // Deduplicate untuk peta
                $dedupedData = $this->deduplicateDataForMap($data);
                
                $totalHasil = $dedupedData->sum('hasil');
                
                $yearlyData[] = [
                    'tahun' => $tahun,
                    'total_hasil' => round($totalHasil, 2)
                ];
            }
            
            // Sort by year
            usort($yearlyData, function($a, $b) {
                return $a['tahun'] <=> $b['tahun'];
            });

            return response()->json([
                'success' => true,
                'data' => $yearlyData
            ]);
            
        } catch (\Throwable $e) {
            \Log::error("Error in getYearlyComparison: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data tahunan: ' . $e->getMessage()
            ], 500);
        }
    }
}