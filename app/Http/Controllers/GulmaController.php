<?php

namespace App\Http\Controllers;

use App\Models\DataGulma;
use Illuminate\Http\Request;

class GulmaController extends Controller
{
    /**
     * Get GeoJSON dengan data gulma yang sudah dimergde
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
        $geoJsonPath = storage_path("../datafix/Wil{$wilayahId}.geojson");
        
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
     * Get statistics
     */
    public function getStatistics()
    {
        $totalData = DataGulma::count();
        $wilayahAktif = DataGulma::distinct('wilayah_id')->count('wilayah_id');
        
        $statusCount = DataGulma::selectRaw('status_gulma, COUNT(*) as count')
            ->groupBy('status_gulma')
            ->pluck('count', 'status_gulma');

        return response()->json([
            'success' => true,
            'total_data' => $totalData,
            'wilayah_aktif' => $wilayahAktif,
            'status_count' => $statusCount
        ]);
    }
}
