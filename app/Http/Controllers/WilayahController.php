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
    public function getGeojson($wilayah_number, Request $request): JsonResponse
    {
        try {
            \Log::info("🗺️ === Getting GeoJSON for Wilayah {$wilayah_number} ===");
            
            // ✅ DEBUG: Log ALL request parameters
            \Log::info("📝 [getGeojson] Request params: " . json_encode($request->all()));
            \Log::info("📝 [getGeojson] Query params: " . json_encode($request->query()));
            
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
            $importId = $request->query('import_id');
            
            // NEW: Get tahun/bulan/minggu parameters untuk periode-specific data
            $tahun = $request->query('tahun');
            $bulan = $request->query('bulan');
            $minggu = $request->query('minggu');
            
            // ✅ FIX: Consistent caching logic
            $timestamp = $request->query('_t');
            $shouldCache = !$importId && !$timestamp && !$tahun && !$bulan && !$minggu && !$isAdmin;
            
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

            // ✅ FIX: Get data from LATEST PUBLISHED OR SPECIFIC PERIOD
            $query = DataGulma::where('wilayah_id', $wilayah_number);

            if ($importId) {
                \Log::info("Using specific import_id from parameter: {$importId}");
                $query->where('import_log_id', $importId);
            } elseif ($tahun && $bulan && $minggu) {
                // Look up publication by period
                \Log::info("🔍 [getGeojson] Looking up publication for period: {$tahun}/{$bulan}/W{$minggu}");
                \Log::info("🔍 [getGeojson] Query params: tahun={$tahun}, bulan={$bulan}, minggu={$minggu}");
                
                $publication = \App\Models\MapPublication::where('status', 'published')
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->where('minggu', $minggu)
                    ->first();
                
                \Log::info("🔍 [getGeojson] Publication found: " . ($publication ? "YES (ID: {$publication->id}, import_log_id: {$publication->import_log_id})" : "NO"));
                
                if ($publication && $publication->import_log_id) {
                    \Log::info("✅ [getGeojson] Using import_id from period: {$publication->import_log_id}");
                    $query->where('import_log_id', $publication->import_log_id);
                } else {
                    \Log::warning("⚠️ [getGeojson] No published data found for period {$tahun}/{$bulan}/W{$minggu}!");
                    $geojson['features'] = [];
                    return response()->json($geojson);
                }
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
                            // SEMUA FIELD SESUAI CSV FORMAT
                            $feature['properties']['seksi'] = (string)$firstRecord->seksi;
                            $feature['properties']['pg'] = (string)$firstRecord->pg;
                            $feature['properties']['fm'] = (string)$firstRecord->fm;
                            $feature['properties']['neto'] = (string)$totalNeto;
                            $feature['properties']['hasil'] = (string)$firstRecord->hasil;
                            $feature['properties']['umur'] = (string)$firstRecord->umur;  // CSV = UMUR_TNM, DB = umur
                            $feature['properties']['tnm_sts'] = (string)$firstRecord->tnm_sts;  // TNM Status
                            $feature['properties']['activitas'] = (string)$firstRecord->activitas;
                            $feature['properties']['penanggungjawab'] = (string)($firstRecord->penanggungjawab ?? '-');
                            $feature['properties']['kode_aktf'] = (string)($firstRecord->kode_aktf ?? '-');
                            $feature['properties']['kategori'] = (string)($bestKategori ?? '');
                            $feature['properties']['tk_ha'] = (string)$totalTk;
                            $feature['properties']['total_tk'] = (string)$firstRecord->total_tk;
                            $feature['properties']['tanggal'] = (string)$firstRecord->tanggal;
                            // Format tanggal sesuai CSV: "2-Nov" bukan ISO timestamp
                            // $tanggalFormatted = $firstRecord->tanggal ? \Carbon\Carbon::parse($firstRecord->tanggal)->format('d-M') : '';
                            // $feature['properties']['tanggal'] = $tanggalFormatted;
                            
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
     * ✅ NEW: Support periode parameter (tahun, bulan, minggu)
     */
    public function getData(Request $request): JsonResponse
    {
        try {
            \Log::info("🔍 === getData() - Loading wilayah summary with CSV stats ===");
            
            // ✅ DEBUG: Log ALL request details
            \Log::info("📝 [getData] Full URL: " . $request->fullUrl());
            \Log::info("📝 [getData] All query params: " . json_encode($request->query()));
            \Log::info("📝 [getData] All input: " . json_encode($request->all()));
            
            // Get periode parameters from request (with null safety)
            $tahun = $request ? $request->query('tahun') : null;
            $bulan = $request ? $request->query('bulan') : null;
            $minggu = $request ? $request->query('minggu') : null;
            
            \Log::info("📝 [getData] Extracted params: tahun={$tahun}, bulan={$bulan}, minggu={$minggu}");
            
            // Determine which publication to use
            $latestPublication = null;
            
            if ($tahun && $bulan && $minggu) {
                // ✅ NEW: Use specific period if provided
                \Log::info("📅 getData() - Looking for period: {$tahun}/{$bulan}/W{$minggu}");
                $latestPublication = \App\Models\MapPublication::where('status', 'published')
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->where('minggu', $minggu)
                    ->first();
                
                // ✅ FIXED: NO FALLBACK! If period not found, return error
                if (!$latestPublication) {
                    \Log::warning("⚠️ No published data found for period {$tahun}/{$bulan}/W{$minggu}!");
                    return response()->json([
                        'data' => [],
                        'total_wilayah' => 0,
                        'error' => "No published data found for period {$tahun}/{$bulan}/W{$minggu}"
                    ], 404);
                }
            } else {
                // ✅ DEFAULT: Use latest published
                $latestPublication = \App\Models\MapPublication::where('status', 'published')
                    ->orderBy('published_at', 'desc')
                    ->first();
            }
            
            if (!$latestPublication) {
                \Log::warning("No published data found!");
                return response()->json([
                    'data' => [],
                    'total_wilayah' => 0,
                    'error' => 'Belum ada data yang dipublikasikan'
                ]);
            }
            
            $latestImportId = $latestPublication->import_log_id;
            \Log::info("✓ Using import_id: {$latestImportId} for period {$latestPublication->tahun}/{$latestPublication->bulan}/W{$latestPublication->minggu}");
            
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
                            'total_tk' => (float)$data->total_tk,  // ✅ FIXED: Use TOTAL_TK not tk_ha
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
                        
                        $existing->total_tk += (float)$data->total_tk;  // ✅ FIXED: Use TOTAL_TK not tk_ha
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
                    $totalTk += $data->total_tk;  // ✅ FIXED: Use TOTAL_TK not tk_ha
                    
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
     * ✅ NEW: Support periode parameter (tahun, bulan, minggu)
     */
    public function getWilayahStats($wilayah_number, Request $request): JsonResponse
    {
        try {
            \Log::info("📊 === Getting stats for Wilayah {$wilayah_number} ===");
            
            // ✅ DEBUG: Log request details
            \Log::info("📝 [getWilayahStats] Full URL: " . $request->fullUrl());
            \Log::info("📝 [getWilayahStats] All query params: " . json_encode($request->query()));
            
            // Get periode parameters from request
            $tahun = $request->query('tahun');
            $bulan = $request->query('bulan');
            $minggu = $request->query('minggu');
            
            \Log::info("📝 [getWilayahStats] Extracted params: tahun={$tahun}, bulan={$bulan}, minggu={$minggu}");
            
            // Determine which publication to use
            $latestPublication = null;
            
            if ($tahun && $bulan && $minggu) {
                // ✅ NEW: Use specific period if provided
                \Log::info("📅 getWilayahStats() - Looking for period: {$tahun}/{$bulan}/W{$minggu}");
                $latestPublication = \App\Models\MapPublication::where('status', 'published')
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->where('minggu', $minggu)
                    ->first();
                
                // ✅ FIXED: NO FALLBACK! If period not found, return error
                if (!$latestPublication) {
                    \Log::warning("⚠️ No published data found for period {$tahun}/{$bulan}/W{$minggu}!");
                    return response()->json([
                        'error' => "No published data found for period {$tahun}/{$bulan}/W{$minggu}",
                        'wilayah_id' => $wilayah_number,
                        'total_records' => 0,
                        'bersih_count' => 0,
                        'ringan_count' => 0,
                        'sedang_count' => 0,
                        'berat_count' => 0,
                        'total_tk' => 0,
                        'total_neto' => 0
                    ], 404);
                }
            } else {
                // ✅ DEFAULT: Use latest published
                $latestPublication = \App\Models\MapPublication::where('status', 'published')
                    ->orderBy('published_at', 'desc')
                    ->first();
            }
            
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
            
            \Log::info("Total raw database records for Wilayah {$wilayah_number}: " . $data->count());
            
            // ✅ CRITICAL FIX: Calculate stats using SAME LOGIC as GeoJSON merge
            // Only count records that have matching lokasi in GeoJSON features
            // (This ensures frontend stats match what's actually displayed on map)
            
            // Load GeoJSON file to get all possible lokasi
            $filePath = base_path("datala/Wil{$wilayah_number}.geojson");
            if (!file_exists($filePath)) {
                return response()->json([
                    'error' => 'GeoJSON file not found',
                    'wilayah_id' => $wilayah_number
                ], 404);
            }
            
            $geojson = json_decode(file_get_contents($filePath), true);
            
            // Create map of all records by seksi
            $gulmaMap = [];
            foreach ($data as $dbRecord) {
                $normalizedSeksi = strtolower(trim($dbRecord->seksi));
                if (!isset($gulmaMap[$normalizedSeksi])) {
                    $gulmaMap[$normalizedSeksi] = [];
                }
                $gulmaMap[$normalizedSeksi][] = $dbRecord;
            }
            
            // Count only records that match GeoJSON features (simulating merge logic)
            $bersihCount = 0;
            $ringanCount = 0;
            $sedangCount = 0;
            $beratCount = 0;
            $mergedCount = 0;
            
            if (isset($geojson['features'])) {
                foreach ($geojson['features'] as $feature) {
                    if (isset($feature['properties'])) {
                        $seksiValue = $feature['properties']['Lokasi'] 
                                ?? $feature['properties']['SEKSI'] 
                                ?? $feature['properties']['Seksi'] 
                                ?? $feature['properties']['seksi']
                                ?? null;
                        
                        $normalizedSeksiValue = $seksiValue ? strtolower(trim($seksiValue)) : null;
                        
                        if ($normalizedSeksiValue && isset($gulmaMap[$normalizedSeksiValue])) {
                            // Found matching records for this feature
                            $records = $gulmaMap[$normalizedSeksiValue];
                            $mergedCount++;
                            
                            // Select BEST kategori (following exact getGeojson logic)
                            $kategoriValues = ['bersih' => 1, 'ringan' => 2, 'sedang' => 3, 'berat' => 4];
                            $bestKategori = null;
                            $bestValue = 999;
                            
                            foreach ($records as $rec) {
                                // Find best kategori
                                $dataValue = $kategoriValues[strtolower($rec->kategori ?? 'berat')] ?? 5;
                                if ($dataValue < $bestValue) {
                                    $bestValue = $dataValue;
                                    $bestKategori = $rec->kategori;
                                }
                            }
                            
                            // Count by best kategori
                            $kategoriLower = strtolower($bestKategori ?? '');
                            if (strpos($kategoriLower, 'bersih') !== false) {
                                $bersihCount++;
                            } elseif (strpos($kategoriLower, 'ringan') !== false) {
                                $ringanCount++;
                            } elseif (strpos($kategoriLower, 'sedang') !== false) {
                                $sedangCount++;
                            } elseif (strpos($kategoriLower, 'berat') !== false) {
                                $beratCount++;
                            }
                        }
                    }
                }
            }
            
            \Log::info("Merged records for Wilayah {$wilayah_number}: " . $mergedCount . " (Bersih: $bersihCount, Ringan: $ringanCount, Sedang: $sedangCount, Berat: $beratCount)");
            
            // Calculate totals from ALL raw records (not just merged ones)
            $totalTk = (float)$data->sum('total_tk');
            $totalNeto = (float)$data->sum('neto');
            
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
     * ✅ NEW: Support periode parameter (tahun, bulan, minggu)
     */
    public function getWilayahRecords($wilayah_number, Request $request): JsonResponse
    {
        try {
            \Log::info("📋 === Getting all records for Wilayah {$wilayah_number} ===");
            
            // Get periode parameters from request
            $tahun = $request->query('tahun');
            $bulan = $request->query('bulan');
            $minggu = $request->query('minggu');
            
            // Determine which publication to use
            $latestPublication = null;
            
            if ($tahun && $bulan && $minggu) {
                // ✅ NEW: Use specific period if provided
                \Log::info("📅 getWilayahRecords() - Looking for period: {$tahun}/{$bulan}/W{$minggu}");
                $latestPublication = \App\Models\MapPublication::where('status', 'published')
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->where('minggu', $minggu)
                    ->first();
                
                // ✅ FIXED: NO FALLBACK! If period not found, return error
                if (!$latestPublication) {
                    \Log::warning("⚠️ No published data found for period {$tahun}/{$bulan}/W{$minggu}!");
                    return response()->json([
                        'error' => "No published data found for period {$tahun}/{$bulan}/W{$minggu}",
                        'records' => []
                    ], 404);
                }
            } else {
                // ✅ DEFAULT: Use latest published
                $latestPublication = \App\Models\MapPublication::where('status', 'published')
                    ->orderBy('published_at', 'desc')
                    ->first();
            }
            
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
            
            // Debug: Log first 3 records
            if ($records->count() > 0) {
                $firstRecord = $records->first();
                \Log::info("✅ First record details:", [
                    'seksi' => $firstRecord->seksi,
                    'hasil' => $firstRecord->hasil,
                    'umur' => $firstRecord->umur,
                    'tnm_sts' => $firstRecord->tnm_sts,
                    'activitas' => $firstRecord->activitas,
                    'tanggal' => $firstRecord->tanggal,
                    'total_tk' => $firstRecord->total_tk,
                    'pg' => $firstRecord->pg,
                    'fm' => $firstRecord->fm
                ]);
            }
            
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
                        'SEKSI' => $record->seksi,
                        'kategori' => $record->kategori,
                        'KATEGORI' => $record->kategori,
                        'status_gulma' => $record->status_gulma,
                        'tk_ha' => $record->tk_ha,
                        'TK/HA' => $record->tk_ha,
                        'TKHA' => $record->tk_ha,
                        'neto' => $record->neto,
                        'NETO' => $record->neto,
                        'Hasil' => $record->hasil,
                        'HASIL' => $record->hasil,
                        // Aktivitas field variations
                        'activitas' => $record->activitas,
                        'aktivitas' => $record->activitas,
                        'ACTIVITAS' => $record->activitas,
                        'AKTIVITAS' => $record->activitas,
                        'activity' => $record->activitas,
                        'Activity' => $record->activitas,
                        'ACTIVITY' => $record->activitas,
                        'Weeding' => $record->activitas,
                        'weeding' => $record->activitas,
                        'WEEDING' => $record->activitas,
                        // Umur field variations
                        'umur_tanaman' => $record->umur,
                        'umur' => $record->umur,
                        'UMUR_TNM' => $record->umur,
                        'Umur Tanaman' => $record->umur,
                        'Umur TNM' => $record->umur,
                        'UMUR' => $record->umur,
                        'Umur' => $record->umur,
                        // TNM_STS field variations
                        'tnm_sts' => $record->tnm_sts,
                        'TNM_STS' => $record->tnm_sts,
                        'TNM STS' => $record->tnm_sts,
                        'tnm' => $record->tnm_sts,
                        'TNM' => $record->tnm_sts,
                        'status_tanaman' => $record->tnm_sts,
                        'STATUS_TANAMAN' => $record->tnm_sts,
                        // Other fields
                        'penanggungjawab' => $record->penanggungjawab ?? '-',
                        'Penanggungjawab' => $record->penanggungjawab ?? '-',
                        'nama' => $record->penanggungjawab ?? '-',
                        'kode_aktf' => $record->kode_aktf ?? '-',
                        'Kode Aktf' => $record->kode_aktf ?? '-',
                        'kode' => $record->kode_aktf ?? '-',
                        // Tanggal field variations
                        'tanggal' => $record->tanggal,
                        'tanggal_rencana_aplikasi' => $record->tanggal,
                        'TANGGAL' => $record->tanggal,
                        'Tanggal' => $record->tanggal,
                        'Tanggal Rencana Aplikasi' => $record->tanggal,
                        'tanggal_aplikasi' => $record->tanggal,
                        'TANGGAL_APLIKASI' => $record->tanggal,
                        // PG and FM
                        'pg' => $record->pg,
                        'PG' => $record->pg,
                        'fm' => $record->fm,
                        'FM' => $record->fm,
                        // Total TK field variations
                        'total_tk' => $record->total_tk,
                        'TOTAL_TK' => $record->total_tk,
                        'TOTAL TK' => $record->total_tk,
                        'totalTk' => $record->total_tk,
                        'WIL' => $wilayah_number
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
     * Return structure for SMART FILTERING of dropdown buttons
     * ✅ LOGIC: Only show last 3 years (from max year in database)
     */
    public function getPeriods(): JsonResponse
    {
        try {
            $maxYears = 3;  // Show last 3 publication years

            // Ambil 3 tahun terbaru yang sudah dipublikasi (tidak harus berurutan)
            $latestYears = \App\Models\MapPublication::where('status', 'published')
                ->select('tahun')
                ->distinct()
                ->orderBy('tahun', 'desc')
                ->limit($maxYears)
                ->pluck('tahun')
                ->toArray();

            if (empty($latestYears)) {
                // No published data
                return response()->json([
                    'success' => true,
                    'periods' => [],
                    'tahun_list' => [],
                    'filter_structure' => [],
                    'latest_period' => null,
                    'year_range' => [
                        'current_year' => now()->year,
                        'min_year' => null,
                        'max_years' => $maxYears,
                        'included_years' => []
                    ]
                ])
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
            }
            
            $maxYearInDb = $latestYears[0];
            $minYear = min($latestYears);

            \Log::info("🔍 getPeriods() - Years included: " . implode(', ', $latestYears));

            // Filter publikasi hanya untuk tahun yang diseleksi
            $publications = \App\Models\MapPublication::where('status', 'published')
                ->whereIn('tahun', $latestYears)
                ->with('importLog')
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->orderBy('minggu', 'desc')
                ->get();
            
            \Log::info("🔍 getPeriods() - Found " . $publications->count() . " published records");
            foreach ($publications as $pub) {
                \Log::info("  ID: {$pub->id} | {$pub->tahun}/{$pub->bulan}/W{$pub->minggu} | Import: {$pub->import_log_id}");
            }
            
            $periods = $publications->map(function($pub) {
                return [
                    'tahun' => $pub->tahun,
                    'bulan' => $pub->bulan,
                    'minggu' => $pub->minggu,
                    'import_log_id' => $pub->import_log_id,
                    'published_at' => $pub->published_at,
                    'periode_key' => $pub->tahun . '-' . $pub->bulan . '-' . $pub->minggu
                ];
            });

            // Build nested structure for smart filtering
            // Structure: { tahun: { bulan: { minggu: true } } }
            $filterStructure = [];
            $tahun_list = [];
            $tahun_bulan_list = [];
            
            foreach ($publications as $pub) {
                // Add to tahun list
                if (!in_array($pub->tahun, $tahun_list)) {
                    $tahun_list[] = $pub->tahun;
                }
                
                // Build nested structure
                if (!isset($filterStructure[$pub->tahun])) {
                    $filterStructure[$pub->tahun] = [];
                }
                if (!isset($filterStructure[$pub->tahun][$pub->bulan])) {
                    $filterStructure[$pub->tahun][$pub->bulan] = [];
                }
                if (!in_array($pub->minggu, $filterStructure[$pub->tahun][$pub->bulan])) {
                    $filterStructure[$pub->tahun][$pub->bulan][] = $pub->minggu;
                }
                
                // Track tahun-bulan pairs for secondary filtering
                $key = $pub->tahun . '-' . $pub->bulan;
                if (!in_array($key, $tahun_bulan_list)) {
                    $tahun_bulan_list[] = $key;
                }
            }
            
            // Sort arrays
            $tahun_list = array_values(array_unique($tahun_list));
            rsort($tahun_list); // Descending (newest first)
            
            \Log::info("📊 getPeriods() - Filter Structure: " . json_encode($filterStructure));
            \Log::info("📅 getPeriods() - Tahun List: " . json_encode($tahun_list));
            
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
                'filter_structure' => $filterStructure,  // NEW: Nested structure for smart filtering
                'latest_period' => $latestPeriod,
                'year_range' => [
                    'max_year_in_db' => $maxYearInDb,
                        'min_year' => $minYear,
                        'max_years' => $maxYears,
                        'included_years' => $latestYears
                ]
            ])
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
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

            // ✅ FIX: Check MapPublication (published data) instead of ImportLog
            // This ensures we validate against data yang sudah dipublikasi dari dashboard
            $publication = \App\Models\MapPublication::where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('minggu', $minggu)
                ->first();

            if (!$publication) {
                // Periode yang dipilih belum dipublikasi, cari latest published period
                $latestPublication = \App\Models\MapPublication::whereNotNull('tahun')
                    ->latest('created_at')
                    ->first();

                if (!$latestPublication) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak ada data yang tersedia',
                        'data_available' => false
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => "Data untuk periode {$tahun} Bulan {$bulan} Minggu {$minggu} belum dipublikasi. Menampilkan data terbaru yang tersedia.",
                    'data_available' => false,
                    'showing_latest' => true,
                    'period' => [
                        'tahun' => $latestPublication->tahun,
                        'bulan' => $latestPublication->bulan,
                        'minggu' => $latestPublication->minggu
                    ],
                    'publication_id' => $latestPublication->id
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'data_available' => true,
                'period' => [
                    'tahun' => $publication->tahun,
                    'bulan' => $publication->bulan,
                    'minggu' => $publication->minggu
                ],
                'publication_id' => $publication->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}