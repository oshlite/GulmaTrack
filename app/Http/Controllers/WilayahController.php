<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Utils\CoordinateTransformer;

class WilayahController extends Controller
{
    /**
     * Get GeoJSON data for specific wilayah with coordinate conversion
     */
    public function getGeojson($wilayah_number): JsonResponse
    {
        try {
            $filePath = storage_path("../data/Wil{$wilayah_number}.geojson");

            if (!file_exists($filePath)) {
                return response()->json([
                    'error' => "GeoJSON file for Wil{$wilayah_number} not found"
                ], 404);
            }

            $geojson = json_decode(file_get_contents($filePath), true);
            
            // Convert dari UTM Zone 48S ke WGS84
            $geojson = CoordinateTransformer::convertGeoJsonToWgs84($geojson);

            return response()->json($geojson);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load GeoJSON: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get summary data for all wilayah
     */
    public function getData(): JsonResponse
    {
        try {
            $dataPath = storage_path('../data');
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
}
