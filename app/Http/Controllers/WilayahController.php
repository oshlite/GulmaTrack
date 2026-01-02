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

            $filePath = base_path("dataya/Wil{$wilayah_number}.geojson");
            
            if (!file_exists($filePath)) {
                return response()->json([
                    'error' => "GeoJSON file not found",
                    'features' => []
                ], 404);
            }

            // Get import_id dari parameter (for specific imports)
            $importId = $request ? $request->query('import_id') : null;
            
            // ✅ FIX: Consistent caching logic
            // - NEVER cache if import_id specified
            // - NEVER cache if admin (always fresh data)
            // - Cache for public only
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
                
                // Only cache for public
                if ($shouldCache) {
                    \Cache::put($cacheKey, $geojson, 3600);
                    \Log::info("✓ Cached GeoJSON for wilayah {$wilayah_number}");
                }
            }

            // ✅ FIX: SIMPLIFIED data selection logic
            // BOTH admin and public get LATEST PUBLISHED data by default
            $query = DataGulma::where('wilayah_id', $wilayah_number);

            if ($importId) {
                // Case 1: Specific import requested (dari URL parameter)
                \Log::info("Using specific import_id from parameter: {$importId}");
                $query->where('import_log_id', $importId);
            } else {
                // Case 2: Get LATEST PUBLISHED data (for both admin & public)
                $latestPublication = \App\Models\MapPublication::where('status', 'published')
                    ->orderBy('published_at', 'desc')
                    ->first();
                
                if ($latestPublication && $latestPublication->import_log_id) {
                    \Log::info("Using LATEST PUBLISHED import_id: {$latestPublication->import_log_id}");
                    $query->where('import_log_id', $latestPublication->import_log_id);
                } else {
                    \Log::warning("No published data found!");
                    // Return empty features
                    $geojson['features'] = [];
                    return response()->json($geojson);
                }
            }
            
            $gulmaData = $query->get();
            \Log::info("Loaded {$gulmaData->count()} records for wilayah {$wilayah_number}");

            // Merge data
            $gulmaMap = [];
            foreach ($gulmaData as $data) {
                $normalizedSeksi = strtolower(trim($data->seksi));
                $gulmaMap[$normalizedSeksi] = $data;
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
                            $data = $gulmaMap[$normalizedSeksiValue];
                            
                            // Merge all CSV data
                            $feature['properties']['seksi'] = $data->seksi;
                            $feature['properties']['pg'] = $data->pg;
                            $feature['properties']['fm'] = $data->fm;
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
                            
                            $mergedCount++;
                        } else {
                            // No data for this feature
                            $feature['properties']['kategori'] = '';
                        }
                    }
                }
                unset($feature);
            }
            
            \Log::info("Merged {$mergedCount} features with database data");

            // ✅ Cache control headers
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
            // Cache wilayah summary untuk 1 jam
            $cacheKey = 'wilayah_summary_all';
            $cacheTTL = 3600;
            
            $cachedData = \Cache::get($cacheKey);
            if ($cachedData) {
                \Log::info("Cache hit for wilayah summary");
                return response()->json($cachedData)
                    ->header('Cache-Control', 'public, max-age=3600')
                    ->header('Expires', \Carbon\Carbon::now()->addHours(1)->toRfc7231String());
            }
            
            // Use base_path instead of storage_path for dataya folder
            $dataPath = base_path('dataya');
            $files = glob("{$dataPath}/Wil*.geojson");

            $wilayahSummary = [];

            foreach ($files as $file) {
                // Use cached converted GeoJSON jika ada
                $wilayahMatch = preg_match('/Wil(\d+)\.geojson/', basename($file), $matches);
                if (!$wilayahMatch) continue;
                
                $wilayahNum = $matches[1];
                $cacheKey = "geojson_wgs84_wil_{$wilayahNum}";
                
                $geojson = \Cache::get($cacheKey);
                if (!$geojson) {
                    $geojson = json_decode(file_get_contents($file), true);
                    // Convert ke WGS84
                    $geojson = CoordinateTransformer::convertGeoJsonToWgs84($geojson);
                    \Cache::put($cacheKey, $geojson, 3600);
                }
                
                $filename = basename($file, '.geojson');

                if (!isset($geojson['features']) || empty($geojson['features'])) {
                    continue;
                }

                // Calculate summary from features
                $totalArea = 0;
                $totalNettoArea = 0;
                $featureCount = count($geojson['features']);
                $statuses = [];

                foreach ($geojson['features'] as $feature) {
                    if (isset($feature['properties'])) {
                        $props = $feature['properties'];
                        
                        // Handle both field name formats (Luas_Bruto or Bruto)
                        $bruto = 0;
                        $netto = 0;
                        
                        // Try Luas_Bruto first (Wil16-18 format)
                        if (isset($props['Luas_Bruto'])) {
                            $bruto = floatval($props['Luas_Bruto']);
                        }
                        // Try Bruto (Wil19-23 format with comma)
                        elseif (isset($props['Bruto'])) {
                            $bruto = floatval(str_replace(',', '.', $props['Bruto']));
                        }
                        
                        // Try Luas_Netto first (Wil16-18 format)
                        if (isset($props['Luas_Netto'])) {
                            $netto = floatval($props['Luas_Netto']);
                        }
                        // Try Netto (Wil20-23 format with comma and capital N)
                        elseif (isset($props['Netto'])) {
                            $netto = floatval(str_replace(',', '.', $props['Netto']));
                        }
                        // Try netto (lowercase, just in case)
                        elseif (isset($props['netto'])) {
                            $netto = floatval(str_replace(',', '.', $props['netto']));
                        }
                        
                        $totalArea += $bruto;
                        $totalNettoArea += $netto;
                        
                        if (isset($props['Status'])) {
                            $statuses[] = $props['Status'];
                        }
                    }
                }

                $wilayahSummary[] = [
                    'wilayah' => str_replace('Wil', '', $filename),
                    'file' => $filename,
                    'feature_count' => $featureCount,
                    'total_area' => round($totalArea, 2),
                    'total_netto_area' => round($totalNettoArea, 2),
                    'status_types' => array_unique($statuses),
                ];
            }

            // Sort by wilayah number
            usort($wilayahSummary, function ($a, $b) {
                return (int)$a['wilayah'] - (int)$b['wilayah'];
            });

            $responseData = [
                'data' => $wilayahSummary,
                'total_wilayah' => count($wilayahSummary),
                'crs' => 'EPSG:4326 (WGS84)'
            ];
            
            // Cache the summary response
            \Cache::put('wilayah_summary_all', $responseData, 3600);

            return response()->json($responseData)
                ->header('Cache-Control', 'public, max-age=3600')
                ->header('Expires', \Carbon\Carbon::now()->addHours(1)->toRfc7231String());
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load data: ' . $e->getMessage()
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
     * Get available periods (tahun, bulan, minggu) from import logs
     */
    public function getPeriods(): JsonResponse
    {
        try {
            // Get all published periods
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

            // Get unique years
            $tahun_list = $periods->pluck('tahun')->unique()->values();
            
            // Get latest published
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

            // Check if data exists for this period
            $importLog = \App\Models\ImportLog::where('status', 'success')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('minggu', $minggu)
                ->first();

            if (!$importLog) {
                // Return latest data instead with a message
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