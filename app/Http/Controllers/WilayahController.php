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
    public function getGeojson($wilayah_number): JsonResponse
    {
        try {
            \Log::info("=== Getting GeoJSON for Wilayah {$wilayah_number} ===");
            
            // Check if map is published for guest users
            if (!auth()->check() || !auth()->user()->is_admin) {
                $isPublished = \App\Models\MapPublication::isDataPublished();
                if (!$isPublished) {
                    return response()->json([
                        'error' => 'Peta belum dipublikasikan oleh admin',
                        'features' => []
                    ], 200); // Return empty data instead of error
                }
            }

            // Use base_path instead of storage_path for datafix folder
            $filePath = base_path("datafix/Wil{$wilayah_number}.geojson");
            \Log::info("File path: {$filePath}");
            \Log::info("File exists: " . (file_exists($filePath) ? 'YES' : 'NO'));

            if (!file_exists($filePath)) {
                \Log::error("GeoJSON file not found: {$filePath}");
                return response()->json([
                    'error' => "GeoJSON file for Wil{$wilayah_number} not found",
                    'features' => []
                ], 404);
            }

            $geojson = json_decode(file_get_contents($filePath), true);
            \Log::info("Original GeoJSON features count: " . (isset($geojson['features']) ? count($geojson['features']) : 0));
            
            // Convert dari UTM Zone 48S ke WGS84
            $geojson = CoordinateTransformer::convertGeoJsonToWgs84($geojson);
            \Log::info("After conversion features count: " . (isset($geojson['features']) ? count($geojson['features']) : 0));

            // Get all data from database for this wilayah
            $gulmaData = DataGulma::where('wilayah_id', $wilayah_number)->get();
            \Log::info("Database records for wilayah {$wilayah_number}: " . $gulmaData->count());
            
            // Create a lookup map by id_feature
            $gulmaMap = [];
            foreach ($gulmaData as $data) {
                $gulmaMap[$data->id_feature] = $data;
            }
            \Log::info("Gulma map size: " . count($gulmaMap));

            // Merge data into GeoJSON features
            $mergedCount = 0;
            if (isset($geojson['features'])) {
                foreach ($geojson['features'] as &$feature) {
                    if (isset($feature['properties'])) {
                        // Try to get id_feature from various property names
                        $idFeature = $feature['properties']['Lokasi'] 
                                  ?? $feature['properties']['SEKSI'] 
                                  ?? $feature['properties']['Seksi'] 
                                  ?? $feature['properties']['seksi']
                                  ?? $feature['properties']['id_feature']
                                  ?? null;

                        // If we found a matching id_feature in database, merge the data
                        if ($idFeature && isset($gulmaMap[$idFeature])) {
                            $data = $gulmaMap[$idFeature];
                            
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
                            
                            $mergedCount++;
                        }
                    }
                }
                unset($feature); // Break reference
            }
            
            \Log::info("Merged {$mergedCount} features with database data");
            \Log::info("Final features count: " . (isset($geojson['features']) ? count($geojson['features']) : 0));

            return response()->json($geojson);
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
            // Use base_path instead of storage_path for datafix folder
            $dataPath = base_path('datafix');
            $files = glob("{$dataPath}/Wil*.geojson");

            $wilayahSummary = [];

            foreach ($files as $file) {
                $geojson = json_decode(file_get_contents($file), true);
                
                // Convert ke WGS84
                $geojson = CoordinateTransformer::convertGeoJsonToWgs84($geojson);
                
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

            return response()->json([
                'data' => $wilayahSummary,
                'total_wilayah' => count($wilayahSummary),
                'crs' => 'EPSG:4326 (WGS84)'
            ]);
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