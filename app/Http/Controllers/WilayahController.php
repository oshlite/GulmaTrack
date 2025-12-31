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
            \Log::info("=== Getting GeoJSON for Wilayah {$wilayah_number} ===");
            
            // Check custom admin header (most reliable)
            $hasAdminHeader = $request && $request->header('X-Admin-Request') === '1';
            
            // Check if user is authenticated admin OR if admin header is passed
            $isAdminParam = $request && $request->query('admin') == '1';
            
            // PENTING: Prioritaskan header dan param, karena session bisa expire
            $isAdmin = $hasAdminHeader || $isAdminParam || (auth()->check() && optional(auth()->user())->is_admin === 1);
            
            \Log::info("X-Admin-Request header: " . ($hasAdminHeader ? 'YES' : 'NO'));
            \Log::info("Admin param: " . ($isAdminParam ? 'YES' : 'NO'));
            \Log::info("User auth: " . (auth()->check() ? 'authenticated' : 'guest'));
            \Log::info("Final is_admin: " . ($isAdmin ? 'YES (ADMIN MODE)' : 'NO (GUEST MODE)'));
            
            // Check if map is published for guest users ONLY
            if (!$isAdmin) {
                $isPublished = \App\Models\MapPublication::isDataPublished();
                \Log::info("Is published: " . ($isPublished ? 'YES' : 'NO'));
                
                if (!$isPublished) {
                    \Log::warning("Guest user accessing unpublished map");
                    return response()->json([
                        'type' => 'FeatureCollection',
                        'features' => []
                    ], 200); // Return empty GeoJSON structure
                }
            } else {
                \Log::info("ADMIN MODE: Will load latest data without publication check");
            }

            // Use base_path instead of storage_path for dataya folder
            $filePath = base_path("dataya/Wil{$wilayah_number}.geojson");
            \Log::info("File path: {$filePath}");
            \Log::info("File exists: " . (file_exists($filePath) ? 'YES' : 'NO'));

            if (!file_exists($filePath)) {
                \Log::error("GeoJSON file not found: {$filePath}");
                return response()->json([
                    'error' => "GeoJSON file for Wil{$wilayah_number} not found",
                    'features' => []
                ], 404);
            }

            // Cache key untuk converted GeoJSON (1 jam cache)
            $cacheKey = "geojson_wgs84_wil_{$wilayah_number}";
            $cacheTTL = 3600; // 1 jam
            
            // Try to get from cache first
            $geojson = \Cache::get($cacheKey);
            
            if (!$geojson) {
                \Log::info("Cache miss for {$cacheKey}, converting coordinates...");
                $geojson = json_decode(file_get_contents($filePath), true);
                \Log::info("Original GeoJSON features count: " . (isset($geojson['features']) ? count($geojson['features']) : 0));
                
                // Convert dari UTM Zone 48S ke WGS84
                $geojson = CoordinateTransformer::convertGeoJsonToWgs84($geojson);
                
                // Store in cache
                \Cache::put($cacheKey, $geojson, $cacheTTL);
                \Log::info("Cached converted GeoJSON for 1 hour");
            } else {
                \Log::info("Cache hit for {$cacheKey}");
            }
            
            \Log::info("After conversion features count: " . (isset($geojson['features']) ? count($geojson['features']) : 0));

            // Get filter parameters if provided
            $tahun = $request ? $request->query('tahun') : null;
            $bulan = $request ? $request->query('bulan') : null;
            $minggu = $request ? $request->query('minggu') : null;

            // Query data from database for this wilayah
            $query = DataGulma::where('wilayah_id', $wilayah_number);

            // If period filters are provided, get only the latest import for that period
            if ($tahun && $bulan && $minggu) {
                // Find the latest import_log_id for this period
                $latestImportLog = \App\Models\ImportLog::where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->where('minggu', $minggu)
                    ->where('status', 'success')
                    ->where('wilayah_id', 'LIKE', "%{$wilayah_number}%")
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($latestImportLog) {
                    \Log::info("Using latest import log ID: {$latestImportLog->id} for period {$tahun}/{$bulan}/W{$minggu}");
                    $query->where('import_log_id', $latestImportLog->id);
                } else {
                    \Log::info("No data found for period {$tahun}/{$bulan}/W{$minggu}, returning empty");
                    // Return empty features if no matching period
                    $geojson['features'] = [];
                    return response()->json($geojson);
                }
            } else {
                // Both guest (when published) and admin should show latest import data
                $latestImportLog = \App\Models\ImportLog::where('status', 'success')
                    ->where('wilayah_id', 'LIKE', "%{$wilayah_number}%")
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if ($latestImportLog) {
                    $userType = $isAdmin ? 'Admin' : 'Guest';
                    \Log::info("{$userType} user: showing latest import_log_id {$latestImportLog->id}");
                    $query->where('import_log_id', $latestImportLog->id);
                } else {
                    \Log::warning("No import logs found for wilayah {$wilayah_number}");
                    // For admin, still show features even if no data in DB
                    // This way map loads but without gulma data
                }
            }
            
            $gulmaData = $query->get();
            \Log::info("Database records for wilayah {$wilayah_number}: " . $gulmaData->count());
            
            // Create a lookup map by seksi (normalized)
            $gulmaMap = [];
            foreach ($gulmaData as $data) {
                // Normalize seksi for matching - remove spaces, lowercase
                $normalizedSeksi = strtolower(trim($data->seksi));
                $gulmaMap[$normalizedSeksi] = $data;
            }
            \Log::info("Gulma map size: " . count($gulmaMap));
            if (count($gulmaMap) > 0) {
                \Log::info("Sample seksi from database: " . implode(', ', array_slice(array_keys($gulmaMap), 0, 5)));
            }

            // Merge data into GeoJSON features
            $mergedCount = 0;
            if (isset($geojson['features'])) {
                // Log sample property names from first feature for debugging
                if (count($geojson['features']) > 0 && isset($geojson['features'][0]['properties'])) {
                    \Log::info("Sample GeoJSON property keys: " . implode(', ', array_keys($geojson['features'][0]['properties'])));
                }
                
                foreach ($geojson['features'] as &$feature) {
                    if (isset($feature['properties'])) {
                        // Try to get seksi from various property names
                        $seksiValue = $feature['properties']['Lokasi'] 
                                  ?? $feature['properties']['SEKSI'] 
                                  ?? $feature['properties']['Seksi'] 
                                  ?? $feature['properties']['seksi']
                                  ?? $feature['properties']['LOKASI']
                                  ?? null;

                        // Normalize seksi for matching
                        $normalizedSeksiValue = $seksiValue ? strtolower(trim($seksiValue)) : null;
                        
                        // If we found a matching seksi in database, merge the data
                        if ($normalizedSeksiValue && isset($gulmaMap[$normalizedSeksiValue])) {
                            $data = $gulmaMap[$normalizedSeksiValue];
                            
                            // Inject semua data CSV ke properties
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
                            
                            // Keep old data jika ada
                            if ($data->status_gulma) {
                                $feature['properties']['status_gulma'] = $data->status_gulma;
                                $feature['properties']['persentase'] = $data->persentase;
                            }
                            
                            $mergedCount++;
                        } else {
                            // Feature tidak ada di database - set kategori kosong (Tidak Ada Data)
                            $feature['properties']['kategori'] = '';
                            $feature['properties']['seksi'] = $feature['properties']['seksi'] ?? '';
                            $feature['properties']['pg'] = '';
                            $feature['properties']['fm'] = '';
                            $feature['properties']['neto'] = '';
                            $feature['properties']['hasil'] = '';
                            $feature['properties']['umur_tanaman'] = '';
                            $feature['properties']['penanggungjawab'] = '';
                            $feature['properties']['kode_aktf'] = '';
                            $feature['properties']['activitas'] = '';
                            $feature['properties']['tk_ha'] = '';
                            $feature['properties']['total_tk'] = '';
                            $feature['properties']['tanggal'] = '';
                        }
                    }
                }
                unset($feature); // Break reference
            }
            
            \Log::info("Merged {$mergedCount} features with database data");
            \Log::info("Final features count: " . (isset($geojson['features']) ? count($geojson['features']) : 0));

            // Add HTTP cache headers - cache for 1 hour
            return response()->json($geojson)
                ->header('Cache-Control', 'public, max-age=3600')
                ->header('Expires', \Carbon\Carbon::now()->addHours(1)->toRfc7231String());
        } catch (\Exception $e) {
            \Log::error("Error in getGeojson: " . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json([
                'error' => 'Failed to load GeoJSON: ' . $e->getMessage(),
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
            $periods = \App\Models\ImportLog::where('status', 'success')
                ->whereNotNull('tahun')
                ->whereNotNull('bulan')
                ->whereNotNull('minggu')
                ->select('tahun', 'bulan', 'minggu')
                ->distinct()
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->orderBy('minggu', 'desc')
                ->get();

            // Get unique years
            $tahun_list = $periods->pluck('tahun')->unique()->values();
            
            // Get latest publication info
            $latest = \App\Models\MapPublication::getLatestPublished();
            $latestPeriod = null;
            
            if ($latest) {
                // Get the import log associated with the latest publication
                // Assuming the latest published is the most recent import
                $latestImport = \App\Models\ImportLog::where('status', 'success')
                    ->whereNotNull('tahun')
                    ->latest('created_at')
                    ->first();
                    
                if ($latestImport) {
                    $latestPeriod = [
                        'tahun' => $latestImport->tahun,
                        'bulan' => $latestImport->bulan,
                        'minggu' => $latestImport->minggu
                    ];
                }
            }

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