<?php

namespace App\Http\Controllers;

use App\Models\DataGulma;
use App\Models\ImportLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GulmaController extends Controller
{
    /**
     * Get GeoJSON dengan data gulma yang sudah merged
     */
    public function getGeoJSONWithData($wilayahId)
    {
        // Validate wilayah ID
        if ($wilayahId < 16 || $wilayahId > 23) {
            return response()->json([
                'error' => 'Wilayah ID harus antara 16-23'
            ], 400);
        }

        // Load GeoJSON file dari datafix
        $geoJsonPath = base_path("datafix/Wil{$wilayahId}.geojson");
        
        if (!file_exists($geoJsonPath)) {
            return response()->json([
                'error' => "File Wil{$wilayahId}.geojson tidak ditemukan"
            ], 404);
        }

        try {
            $geoData = json_decode(file_get_contents($geoJsonPath), true);

            // Get latest data from database for each feature
            $dataGulma = DataGulma::where('wilayah_id', $wilayahId)
                ->latest('tanggal')
                ->get()
                ->keyBy('id_feature');

            // Merge data dengan features
            foreach ($geoData['features'] as &$feature) {
                // Try multiple field names for feature ID
                $featureId = $feature['properties']['id'] ?? 
                            $feature['properties']['SEKSI'] ?? 
                            $feature['properties']['Seksi'] ?? 
                            $feature['properties']['Lokasi'] ?? null;
                
                if ($featureId && isset($dataGulma[$featureId])) {
                    $data = $dataGulma[$featureId];
                    
                    // Inject semua data CSV ke properties
                    $feature['properties']['id_feature'] = $data->id_feature;
                    $feature['properties']['pg'] = $data->pg;
                    $feature['properties']['fm'] = $data->fm;
                    $feature['properties']['seksi'] = $data->seksi;
                    $feature['properties']['neto'] = $data->neto;
                    $feature['properties']['hasil'] = $data->hasil;
                    $feature['properties']['umur_tanaman'] = $data->umur_tanaman;
                    $feature['properties']['penanggungjawab'] = $data->penanggungjawab;
                    $feature['properties']['kode_aktf'] = $data->kode_aktf;
                    $feature['properties']['activitas'] = $data->activitas;
                    $feature['properties']['kategori'] = $data->kategori;
                    $feature['properties']['tk_ha'] = $data->tk_ha;
                    $feature['properties']['total_tk'] = $data->total_tk;
                    $feature['properties']['tanggal'] = $data->tanggal;
                    
                    // Keep old data jika ada
                    if ($data->status_gulma) {
                        $feature['properties']['status_gulma'] = $data->status_gulma;
                        $feature['properties']['persentase'] = $data->persentase;
                    }
                    
                    $feature['properties']['has_data'] = true;
                } else {
                    $feature['properties']['has_data'] = false;
                }
            }

            return response()->json($geoData);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all data gulma dengan filter
     */
    public function getDataGulma(Request $request)
    {
        $wilayahId = $request->query('wilayah_id');
        $status = $request->query('status');

        $query = DataGulma::query();

        if ($wilayahId) {
            $query->where('wilayah_id', $wilayahId);
        }

        if ($status && in_array($status, ['Bersih', 'Ringan', 'Sedang', 'Berat'])) {
            $query->where('status_gulma', $status);
        }

        $data = $query->latest('tanggal')->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $data->items(),
            'total' => $data->total(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
                'per_page' => $data->perPage()
            ]
        ]);
    }

    /**
     * Get statistics (untuk admin dashboard)
     */
    public function getStatistics()
    {
        $totalData = DataGulma::count();
        $wilayahAktif = DataGulma::distinct('wilayah_id')->count('wilayah_id');
        
        $statusCount = DataGulma::selectRaw('status_gulma, COUNT(*) as count')
            ->whereNotNull('status_gulma')
            ->groupBy('status_gulma')
            ->pluck('count', 'status_gulma');

        return response()->json([
            'success' => true,
            'total_data' => $totalData,
            'wilayah_aktif' => $wilayahAktif,
            'status_count' => $statusCount
        ]);
    }

    // ============================================
    // NEW METHODS FOR STATISTIK PAGE
    // ============================================

    /**
     * Get statistik summary untuk halaman statistik
     */
    public function getStatistikSummary(Request $request)
    {
        try {
            $tahun = $request->query('tahun');
            $bulan = $request->query('bulan');
            $minggu = $request->query('minggu');
            
            // Base query
            $query = DataGulma::query();
            
            // Filter by period if provided
            if ($tahun && $bulan && $minggu) {
                $query->whereHas('importLog', function($q) use ($tahun, $bulan, $minggu) {
                    $q->where('tahun', $tahun)
                      ->where('bulan', $bulan)
                      ->where('minggu', $minggu)
                      ->where('status', 'success');
                });
            } else {
                // Get latest period data
                $latestImport = ImportLog::where('status', 'success')
                    ->whereNotNull('tahun')
                    ->latest('created_at')
                    ->first();
                    
                if ($latestImport) {
                    $query->where('import_log_id', $latestImport->id);
                }
            }
            
            // Get summary by wilayah
            $summary = $query->select(
                    'wilayah_id',
                    DB::raw('COUNT(*) as total_plot'),
                    DB::raw('COALESCE(SUM(neto), 0) as total_luas'),
                    DB::raw('COALESCE(AVG(hasil), 0) as avg_hasil'),
                    DB::raw('COALESCE(AVG(umur_tanaman), 0) as avg_umur'),
                    DB::raw('COALESCE(SUM(total_tk), 0) as total_tenaga_kerja')
                )
                ->groupBy('wilayah_id')
                ->orderBy('wilayah_id')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $summary,
                'period' => [
                    'tahun' => $tahun,
                    'bulan' => $bulan,
                    'minggu' => $minggu
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get ranking wilayah berdasarkan hasil
     */
    public function getStatistikRanking(Request $request)
    {
        try {
            $tahun = $request->query('tahun');
            $bulan = $request->query('bulan');
            $minggu = $request->query('minggu');
            
            $query = DataGulma::query();
            
            // Filter by period
            if ($tahun && $bulan && $minggu) {
                $query->whereHas('importLog', function($q) use ($tahun, $bulan, $minggu) {
                    $q->where('tahun', $tahun)
                      ->where('bulan', $bulan)
                      ->where('minggu', $minggu)
                      ->where('status', 'success');
                });
            } else {
                $latestImport = ImportLog::where('status', 'success')
                    ->whereNotNull('tahun')
                    ->latest('created_at')
                    ->first();
                    
                if ($latestImport) {
                    $query->where('import_log_id', $latestImport->id);
                }
            }
            
            // Get ranking
            $ranking = $query->select(
                    'wilayah_id',
                    DB::raw('COALESCE(SUM(hasil), 0) as total_hasil'),
                    DB::raw('COALESCE(AVG(hasil), 0) as avg_hasil_per_ha'),
                    DB::raw('COUNT(*) as jumlah_plot')
                )
                ->whereNotNull('hasil')
                ->groupBy('wilayah_id')
                ->orderBy('total_hasil', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $ranking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get productivity analysis
     */
    public function getStatistikProductivity(Request $request)
    {
        try {
            $query = DataGulma::query();
            
            // Get latest import
            $latestImport = ImportLog::where('status', 'success')
                ->whereNotNull('tahun')
                ->latest('created_at')
                ->first();
                
            if ($latestImport) {
                $query->where('import_log_id', $latestImport->id);
            }
            
            // Kategorisasi berdasarkan hasil (T/Ha)
            $tinggi = $query->clone()->where('hasil', '>', 9)->count();
            $sedang = $query->clone()->whereBetween('hasil', [8, 9])->count();
            $rendah = $query->clone()->where('hasil', '<', 8)->where('hasil', '>', 0)->count();
            
            // Average untuk masing-masing kategori
            $avgTinggi = $query->clone()->where('hasil', '>', 9)->avg('hasil');
            $avgSedang = $query->clone()->whereBetween('hasil', [8, 9])->avg('hasil');
            $avgRendah = $query->clone()->where('hasil', '<', 8)->where('hasil', '>', 0)->avg('hasil');
            
            $productivity = [
                'tinggi' => [
                    'count' => $tinggi,
                    'avg' => round($avgTinggi ?? 0, 2)
                ],
                'sedang' => [
                    'count' => $sedang,
                    'avg' => round($avgSedang ?? 0, 2)
                ],
                'rendah' => [
                    'count' => $rendah,
                    'avg' => round($avgRendah ?? 0, 2)
                ],
            ];
            
            return response()->json([
                'success' => true,
                'data' => $productivity
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get yearly comparison
     */
    public function getYearlyComparison(Request $request)
    {
        try {
            // Get unique years from import logs
            $years = ImportLog::where('status', 'success')
                ->whereNotNull('tahun')
                ->distinct()
                ->pluck('tahun')
                ->sort()
                ->values();
            
            $yearlyHasil = [];
            
            foreach ($years as $year) {
                // Get total hasil for this year
                $totalHasil = DataGulma::whereHas('importLog', function($q) use ($year) {
                        $q->where('tahun', $year)
                          ->where('status', 'success');
                    })
                    ->sum('hasil');
                
                // Count wilayah
                $wilayahCount = DataGulma::whereHas('importLog', function($q) use ($year) {
                        $q->where('tahun', $year)
                          ->where('status', 'success');
                    })
                    ->distinct('wilayah_id')
                    ->count('wilayah_id');
                    
                $yearlyHasil[] = [
                    'tahun' => $year,
                    'total_hasil' => round($totalHasil, 2),
                    'wilayah_count' => $wilayahCount
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $yearlyHasil
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}