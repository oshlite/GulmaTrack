<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Utils\CoordinateTransformer;
use App\Models\DataGulma;

class WilayahController extends Controller
{
    /**
     * Get GeoJSON data for specific wilayah with coordinate conversion
     * and merge with status_gulma data from database
     */
    public function getGeojson($wilayah_number, Request $request = null): JsonResponse
    {
        try {
            \Log::info("🗺️ === Getting GeoJSON for Wilayah {$wilayah_number} ===");
            
            // Check if admin (sama untuk dashboard & wilayah page)
            $hasAdminHeader = $request && $request->header('X-Admin-Request') === '1';
            $isAdminParam = $request && $request->query('admin') == '1';
            $isAdmin = $hasAdminHeader || $isAdminParam || (auth()->check() && optional(auth()->user())->is_admin === 1);
            
            \Log::info("User type: " . ($isAdmin ? 'ADMIN' : 'PUBLIC'));

            $filePath = base_path("datala/Wil{$wilayah_number}.geojson");
            
            if (!file_exists($filePath)) {
                return response()->json([
                    'error' => "GeoJSON file not found",
                    'features' => []
                ], 404);
            }

            // Get import_id dari parameter (for specific imports)
            $importId = $request ? $request->query('import_id') : null;
            
            // ✅ FIX: Consistent caching logic
            $timestamp = $request ? $request->query('_t') : null;
            $shouldCache = !$importId && !$timestamp && !$isAdmin;
            
            $cacheKey = "geojson_wgs84_wil_{$wilayah_number}";
            $geojson = null;
            
            if ($shouldCache) {
                $geojson = \Cache::get($cacheKey);
                if ($geojson) {
                    \Log::info("✓ Cache HIT for wilayah {$wilayah_number}");
                }
            }
            
            if (!$geojson) {
                \Log::info("Loading fresh GeoJSON for wilayah {$wilayah_number}");
                $geojson = json_decode(file_get_contents($filePath), true);
                $geojson = CoordinateTransformer::convertGeoJsonToWgs84($geojson);
                
                if ($shouldCache) {
                    \Cache::put($cacheKey, $geojson, 3600);
                    \Log::info("✓ Cached GeoJSON for wilayah {$wilayah_number}");
                }
            }

            // ✅ FIX: Get data from LATEST PUBLISHED
            $query = DataGulma::where('wilayah_id', $wilayah_number);

            if ($importId) {
                \Log::info("Using specific import_id from parameter: {$importId}");
                $query->where('import_log_id', $importId);
            } else {
                $latestPublication = \App\Models\MapPublication::where('status', 'published')
                    ->orderBy('published_at', 'desc')
                    ->first();
                
                if ($latestPublication && $latestPublication->import_log_id) {
                    \Log::info("Using LATEST PUBLISHED import_id: {$latestPublication->import_log_id}");
                    $query->where('import_log_id', $latestPublication->import_log_id);
                } else {
                    \Log::warning("No published data found!");
                    $geojson['features'] = [];
                    return response()->json($geojson);
                }
            }
            
            // Don't deduplicate - send all records, let frontend aggregate
            $allData = $query->get();
            $gulmaData = collect($allData);
            \Log::info("Loaded " . $gulmaData->count() . " records for wilayah {$wilayah_number}");

            // Create map of all records by seksi (allow duplicates)
            $gulmaMap = [];
            foreach ($gulmaData as $data) {
                $normalizedSeksi = strtolower(trim($data->seksi));
                if (!isset($gulmaMap[$normalizedSeksi])) {
                    $gulmaMap[$normalizedSeksi] = [];
                }
                $gulmaMap[$normalizedSeksi][] = $data;
            }

            $mergedCount = 0;
            if (isset($geojson['features'])) {
                foreach ($geojson['features'] as &$feature) {
                    if (isset($feature['properties'])) {
                        $seksiValue = $feature['properties']['Lokasi'] 
                                ?? $feature['properties']['SEKSI'] 
                                ?? $feature['properties']['Seksi'] 
                                ?? $feature['properties']['seksi']
                                ?? null;

                        $normalizedSeksiValue = $seksiValue ? strtolower(trim($seksiValue)) : null;
                        
                        if ($normalizedSeksiValue && isset($gulmaMap[$normalizedSeksiValue])) {
                            // Get records for this seksi
                            $records = $gulmaMap[$normalizedSeksiValue];
                            
                            // For duplicate lokasi: use BEST kategori but SUM TK values
                            // Kategori preference: Bersih > Ringan > Sedang > Berat
                            $kategoriValues = ['bersih' => 1, 'ringan' => 2, 'sedang' => 3, 'berat' => 4];
                            $bestKategori = null;
                            $bestValue = 999;
                            $totalTk = 0;
                            $totalNeto = 0;
                            $firstRecord = $records[0];
                            
                            foreach ($records as $data) {
                                // Track best kategori
                                $dataValue = $kategoriValues[strtolower($data->kategori ?? 'berat')] ?? 5;
                                if ($dataValue < $bestValue) {
                                    $bestValue = $dataValue;
                                    $bestKategori = $data->kategori;
                                }
                                
                                // Sum TK and Neto
                                $totalTk += (float)$data->tk_ha;
                                $totalNeto += (float)$data->neto;
                            }
                            
                            // Use first record's data, but override with best kategori and summed values
                            $feature['properties']['seksi'] = $firstRecord->seksi;
                            $feature['properties']['pg'] = $firstRecord->pg;
                            $feature['properties']['fm'] = $firstRecord->fm;
                            $feature['properties']['neto'] = $totalNeto;
                            $feature['properties']['hasil'] = $firstRecord->hasil;
                            $feature['properties']['umur_tanaman'] = $firstRecord->umur_tanaman;
                            $feature['properties']['penanggungjawab'] = $firstRecord->penanggungjawab;
                            $feature['properties']['kode_aktf'] = $firstRecord->kode_aktf;
                            $feature['properties']['activitas'] = $firstRecord->activitas;
                            $feature['properties']['kategori'] = $bestKategori ?? '';
                            $feature['properties']['tk_ha'] = $totalTk;
                            $feature['properties']['total_tk'] = $firstRecord->total_tk;
                            $feature['properties']['tanggal'] = $firstRecord->tanggal;
                            
                            $mergedCount++;
                        } else {
                            $feature['properties']['kategori'] = '';
                        }
                    }
                }
                unset($feature);
            }
            
            \Log::info("Merged {$mergedCount} features with database data");

            if ($shouldCache) {
                return response()->json($geojson)
                    ->header('Cache-Control', 'public, max-age=3600');
            } else {
                return response()->json($geojson)
                    ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0');
            }
        } catch (\Exception $e) {
            \Log::error("Error in getGeojson: " . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load GeoJSON',
                'features' => []
            ], 500);
        }
    }

    /**
     * Get summary data for all wilayah
     */
    public function getData(): JsonResponse
    {
        try {
            \Log::info("🔍 === getData() - Loading wilayah summary with CSV stats ===");
            
            // ✅ CRITICAL FIX: Get LATEST PUBLISHED import_log_id
            $latestPublication = \App\Models\MapPublication::where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->first();
            
            if (!$latestPublication) {
                \Log::warning("No published data found!");
                return response()->json([
                    'data' => [],
                    'total_wilayah' => 0,
                    'error' => 'Belum ada data yang dipublikasikan'
                ]);
            }
            
            $latestImportId = $latestPublication->import_log_id;
            \Log::info("✓ Using LATEST PUBLISHED import_id: {$latestImportId}");
            
            $dataPath = base_path('datala');
            $files = glob("{$dataPath}/Wil*.geojson");
            $wilayahSummary = [];

            foreach ($files as $file) {
                $wilayahMatch = preg_match('/Wil(\d+)\.geojson/', basename($file), $matches);
                if (!$wilayahMatch) continue;
                
                $wilayahNum = $matches[1];
                $filename = basename($file, '.geojson');
                
                // Load GeoJSON (for bruto area from features)
                $cacheKey = "geojson_wgs84_wil_{$wilayahNum}";
                $geojson = \Cache::get($cacheKey);
                if (!$geojson) {
                    $geojson = json_decode(file_get_contents($file), true);
                    $geojson = CoordinateTransformer::convertGeoJsonToWgs84($geojson);
                    \Cache::put($cacheKey, $geojson, 3600);
                }
                
                if (!isset($geojson['features']) || empty($geojson['features'])) {
                    continue;
                }

                // ✅ FIX: Get ACCURATE stats from LATEST PUBLISHED CSV data
                $query = DataGulma::where('wilayah_id', $wilayahNum)
                    ->where('import_log_id', $latestImportId);
                
                $allData = $query->get();
                
                // DEDUPLICATE BEFORE COUNTING
                $deduped = [];
                $kategoriValue = ['bersih' => 1, 'ringan' => 2, 'sedang' => 3, 'berat' => 4];
                
                foreach ($allData as $data) {
                    $normalizedSeksi = strtolower(trim($data->seksi));
                    
                    if (!isset($deduped[$normalizedSeksi])) {
                        $deduped[$normalizedSeksi] = (object)[
                            'kategori' => strtolower($data->kategori ?? ''),
                            'neto' => (float)$data->neto,
                            'tk_ha' => (float)$data->tk_ha,
                            'kategoriValue' => $kategoriValue[strtolower($data->kategori ?? 'berat')] ?? 5
                        ];
                    } else {
                        // Keep BEST kategori, SUM TK+Neto
                        $existing = $deduped[$normalizedSeksi];
                        $dataValue = $kategoriValue[strtolower($data->kategori ?? 'berat')] ?? 5;
                        
                        if ($dataValue < $existing->kategoriValue) {
                            $existing->kategori = strtolower($data->kategori ?? '');
                            $existing->kategoriValue = $dataValue;
                        }
                        
                        $existing->tk_ha += (float)$data->tk_ha;
                        $existing->neto += (float)$data->neto;
                    }
                }
                
                // NOW COUNT FROM DEDUPLICATED DATA
                $totalLuasNetto = 0;
                $totalTk = 0;
                $statusCounts = [
                    'bersih' => 0,
                    'ringan' => 0,
                    'sedang' => 0,
                    'berat' => 0,
                    'belum_dimonitoring' => 0
                ];
                
                foreach ($deduped as $data) {
                    $totalLuasNetto += $data->neto;
                    $totalTk += $data->tk_ha;
                    
                    $kategori = $data->kategori;
                    if (!$kategori || (!str_contains($kategori, 'bersih') && !str_contains($kategori, 'ringan') && !str_contains($kategori, 'sedang') && !str_contains($kategori, 'berat'))) {
                        $statusCounts['belum_dimonitoring']++;
                    } elseif (str_contains($kategori, 'bersih')) {
                        $statusCounts['bersih']++;
                    } elseif (str_contains($kategori, 'ringan')) {
                        $statusCounts['ringan']++;
                    } elseif (str_contains($kategori, 'sedang')) {
                        $statusCounts['sedang']++;
                    } elseif (str_contains($kategori, 'berat')) {
                        $statusCounts['berat']++;
                    }
                }
                
                // Calculate total bruto from GeoJSON
                $totalArea = 0;
                foreach ($geojson['features'] as $feature) {
                    if (isset($feature['properties'])) {
                        $props = $feature['properties'];
                        $bruto = 0;
                        
                        if (isset($props['Luas_Bruto'])) {
                            $bruto = floatval($props['Luas_Bruto']);
                        } elseif (isset($props['Bruto'])) {
                            $bruto = floatval(str_replace(',', '.', $props['Bruto']));
                        }
                        
                        $totalArea += $bruto;
                    }
                }
                
                // DEBUG LOG for Wilayah 16
                if ($wilayahNum == 16) {
                    \Log::info("📊 [WILAYAH 16 STATS]", [
                        'raw_records' => $allData->count(),
                        'deduplicated_records' => count($deduped),
                        'total_tk' => round($totalTk, 2),
                        'total_netto' => round($totalLuasNetto, 2),
                        'status_ringan' => $statusCounts['ringan'],
                        'all_status_counts' => $statusCounts
                    ]);
                }

                $wilayahSummary[] = [
                    'wilayah' => str_replace('Wil', '', $filename),
                    'file' => $filename,
                    'feature_count' => count($geojson['features']),
                    'total_area' => round($totalArea, 2),
                    'total_luas_netto' => round($totalLuasNetto, 2),
                    'total_tk' => round($totalTk, 2),
                    'status_counts' => $statusCounts
                ];
            }

            // Sort by wilayah number
            usort($wilayahSummary, function ($a, $b) {
                return (int)$a['wilayah'] - (int)$b['wilayah'];
            });

            $responseData = [
                'data' => $wilayahSummary,
                'total_wilayah' => count($wilayahSummary),
                'crs' => 'EPSG:4326 (WGS84)',
                'import_log_id' => $latestImportId
            ];

            return response()->json($responseData)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        } catch (\Exception $e) {
            \Log::error("Error in getData: " . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get aggregated statistics for specific wilayah directly from database
     * This returns the correct counts and totals for the wilayah cards
     */
    public function getWilayahStats($wilayah_number, Request $request = null): JsonResponse
    {
        try {
            \Log::info("📊 === Getting stats for Wilayah {$wilayah_number} ===");
            
            // Get latest published data
            $latestPublication = \App\Models\MapPublication::where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->first();
            
            if (!$latestPublication) {
                return response()->json([
                    'error' => 'No published data found',
                    'bersih_count' => 0,
                    'ringan_count' => 0,
                    'total_tk' => 0,
                    'total_neto' => 0
                ]);
            }
            
            // Get all records for this wilayah from latest published import
            $data = DataGulma::where('wilayah_id', $wilayah_number)
                ->where('import_log_id', $latestPublication->import_log_id)
                ->get();
            
            \Log::info("Total records for Wilayah {$wilayah_number}: " . $data->count());
            
            // Count by kategori (direct count of all records)
            $bersihCount = $data->where('kategori', 'Bersih')->count();
            $ringanCount = $data->where('kategori', 'Ringan')->count();
            $sedangCount = $data->where('kategori', 'Sedang')->count();
            $beratCount = $data->where('kategori', 'Berat')->count();
            
            // Sum totals (all records contribute) - PAKE TOTAL_TK BUKAN TK_HA
            $totalTk = (float)$data->sum('total_tk');
            $totalNeto = $data->sum('neto');
            
            $response = [
                'wilayah_id' => $wilayah_number,
                'total_records' => $data->count(),
                'bersih_count' => (int)$bersihCount,
                'ringan_count' => (int)$ringanCount,
                'sedang_count' => (int)$sedangCount,
                'berat_count' => (int)$beratCount,
                'total_tk' => (float) round($totalTk, 2),  // Ensure FLOAT type in JSON
                'total_neto' => round($totalNeto, 2),
                'import_log_id' => $latestPublication->import_log_id
            ];
            
            \Log::info("✓ Stats calculated", $response);
            
            return response()->json($response)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
                
        } catch (\Exception $e) {
            \Log::error("Error in getWilayahStats: " . $e->getMessage());
            return response()->json([
                'error' => 'Failed to get stats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all records for specific wilayah directly from database
     * This returns all CSV records (not deduplicated) for location table display
     */
    public function getWilayahRecords($wilayah_number, Request $request = null): JsonResponse
    {
        try {
            \Log::info("📋 === Getting all records for Wilayah {$wilayah_number} ===");
            
            // Get latest published data
            $latestPublication = \App\Models\MapPublication::where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->first();
            
            if (!$latestPublication) {
                return response()->json([
                    'error' => 'No published data found',
                    'records' => []
                ]);
            }
            
            // Get ALL records for this wilayah from latest published import
            $records = DataGulma::where('wilayah_id', $wilayah_number)
                ->where('import_log_id', $latestPublication->import_log_id)
                ->orderBy('seksi')
                ->get();
            
            \Log::info("Total records for Wilayah {$wilayah_number}: " . $records->count());
            
            // Convert to features-like format for frontend compatibility
            $features = [];
            foreach ($records as $record) {
                $features[] = [
                    'type' => 'Feature',
                    'properties' => [
                        'id_feature' => $record->id,
                        'wilayah' => $wilayah_number,
                        'Lokasi' => $record->seksi,
                        'seksi' => $record->seksi,
                        'kategori' => $record->kategori,
                        'status_gulma' => $record->status_gulma,
                        'tk_ha' => $record->tk_ha,
                        'neto' => $record->neto,
                        'hasil' => $record->hasil,
                        'activitas' => $record->activitas,
                        'umur_tanaman' => $record->umur_tanaman,
                        'penanggungjawab' => $record->penanggungjawab,
                        'kode_aktf' => $record->kode_aktf,
                        'tanggal' => $record->tanggal,
                        'pg' => $record->pg,
                        'fm' => $record->fm,
                        'total_tk' => $record->total_tk
                    ]
                ];
            }
            
            return response()->json([
                'features' => $features,
                'total' => $records->count(),
                'wilayah_id' => $wilayah_number,
                'import_log_id' => $latestPublication->import_log_id
            ])
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
                
        } catch (\Exception $e) {
            \Log::error("Error in getWilayahRecords: " . $e->getMessage());
            return response()->json([
                'error' => 'Failed to get records: ' . $e->getMessage(),
                'records' => []
            ], 500);
        }
    }

    /**
     * Display wilayah page
     */
    public function index()
    {
        return view('pages.wilayah');
    }

    /**
     * Get available periods (tahun, bulan, minggu) from publications
     */
    public function getPeriods(): JsonResponse
    {
        try {
            $publications = \App\Models\MapPublication::where('status', 'published')
                ->with('importLog')
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->orderBy('minggu', 'desc')
                ->get();
            
            $periods = $publications->map(function($pub) {
                return [
                    'tahun' => $pub->tahun,
                    'bulan' => $pub->bulan,
                    'minggu' => $pub->minggu,
                    'import_log_id' => $pub->import_log_id,
                    'published_at' => $pub->published_at
                ];
            });

            $tahun_list = $periods->pluck('tahun')->unique()->values();
            
            $latest = $publications->first();
            $latestPeriod = $latest ? [
                'tahun' => $latest->tahun,
                'bulan' => $latest->bulan,
                'minggu' => $latest->minggu
            ] : null;

            return response()->json([
                'success' => true,
                'periods' => $periods,
                'tahun_list' => $tahun_list,
                'latest_period' => $latestPeriod
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get data for specific period
     */
     public function getDataByPeriod(Request $request): JsonResponse
    {
        try {
            $tahun = $request->query('tahun');
            $bulan = $request->query('bulan');
            $minggu = $request->query('minggu');

            $importLog = \App\Models\ImportLog::where('status', 'success')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('minggu', $minggu)
                ->first();

            if (!$importLog) {
                $latestImport = \App\Models\ImportLog::where('status', 'success')
                    ->whereNotNull('tahun')
                    ->latest('created_at')
                    ->first();

                if (!$latestImport) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak ada data yang tersedia',
                        'data_available' => false
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => "Data untuk periode {$tahun} Bulan {$bulan} Minggu {$minggu} tidak tersedia. Menampilkan data terbaru.",
                    'data_available' => false,
                    'showing_latest' => true,
                    'period' => [
                        'tahun' => $latestImport->tahun,
                        'bulan' => $latestImport->bulan,
                        'minggu' => $latestImport->minggu
                    ],
                    'import_log_id' => $latestImport->id
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'data_available' => true,
                'period' => [
                    'tahun' => $importLog->tahun,
                    'bulan' => $importLog->bulan,
                    'minggu' => $importLog->minggu
                ],
                'import_log_id' => $importLog->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}