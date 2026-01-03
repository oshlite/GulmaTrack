<?php

namespace App\Http\Controllers;

use App\Models\DataGulma;
use App\Models\ImportLog;
use App\Models\MapPublication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GulmaController extends Controller
{
    /**
     * Helper: apply period filter safely
     */
    private function applyPeriodFilter($query, Request $request)
    {
        if (
            $request->filled('tahun') ||
            $request->filled('bulan') ||
            $request->filled('minggu')
        ) {
            $query->whereHas('importLog', function ($q) use ($request) {
                if ($request->filled('tahun')) {
                    $q->where('tahun', $request->tahun);
                }
                if ($request->filled('bulan')) {
                    $q->where('bulan', $request->bulan);
                }
                if ($request->filled('minggu')) {
                    $q->where('minggu', $request->minggu);
                }
            });
        }

        return $query;
    }

    /**
     * API: Summary statistik per wilayah
     */
    public function getStatistikSummary(Request $request)
    {
        try {
            // Get latest published import
            $latestPublication = MapPublication::where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->first();

            if (!$latestPublication) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data publikasi yang tersedia',
                    'data' => []
                ]);
            }

            // Get all records from latest published import
            $allData = DataGulma::where('import_log_id', $latestPublication->import_log_id)->get();

            // Group by wilayah_id and calculate aggregates in PHP (like WilayahController)
            $groupedData = $allData->groupBy('wilayah_id')->map(function ($wilayahData) {
                return [
                    'wilayah_id' => $wilayahData->first()->wilayah_id,
                    'total_features' => $wilayahData->count(),
                    'total_neto' => round($wilayahData->sum('neto'), 2),
                    'total_hasil' => round($wilayahData->sum('hasil'), 2),
                    'avg_hasil' => round($wilayahData->avg('hasil'), 2),
                    'avg_umur' => round($wilayahData->avg('umur_tanaman'), 2),
                    'total_tenaga_kerja' => round($wilayahData->sum('tk_ha'), 2)
                ];
            })->values();

            return response()->json([
                'success' => true,
                'data' => $groupedData,
                'filters' => $request->only(['tahun', 'bulan', 'minggu']),
                'published_at' => $latestPublication->published_at
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Query statistik gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Ranking wilayah
     */
    public function getStatistikRanking(Request $request)
    {
        try {
            // Get latest published import
            $latestPublication = MapPublication::where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->first();

            if (!$latestPublication) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data publikasi yang tersedia',
                    'data' => []
                ]);
            }

            // Get all records from latest published import
            $allData = DataGulma::where('import_log_id', $latestPublication->import_log_id)->get();

            // Group by wilayah_id and calculate aggregates in PHP
            $rankingData = $allData->groupBy('wilayah_id')->map(function ($wilayahData) {
                return [
                    'wilayah_id' => $wilayahData->first()->wilayah_id,
                    'total_hasil' => round($wilayahData->sum('hasil'), 2),
                    'avg_hasil' => round($wilayahData->avg('hasil'), 2),
                    'jumlah_features' => $wilayahData->count(),
                    'total_neto' => round($wilayahData->sum('neto'), 2),
                    'total_tenaga_kerja' => round($wilayahData->sum('tk_ha'), 2)
                ];
            })->values()->sortByDesc('total_hasil')->values();

            return response()->json([
                'success' => true,
                'data' => $rankingData,
                'published_at' => $latestPublication->published_at
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil ranking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Produktivitas
     */
    public function getStatistikProductivity(Request $request)
    {
        try {
            $query = DataGulma::select(
                'wilayah_id',
                'id_feature',
                DB::raw('AVG(hasil) AS avg_hasil')
            )
            ->groupBy('wilayah_id', 'id_feature');

            $this->applyPeriodFilter($query, $request);

            $features = $query->get();

            $data = [
                'tinggi' => $features->where('avg_hasil', '>', 9),
                'sedang' => $features->whereBetween('avg_hasil', [8, 9]),
                'rendah' => $features->where('avg_hasil', '<', 8),
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'tinggi' => [
                        'count' => $data['tinggi']->count(),
                        'avg' => round($data['tinggi']->avg('avg_hasil') ?? 0, 2)
                    ],
                    'sedang' => [
                        'count' => $data['sedang']->count(),
                        'avg' => round($data['sedang']->avg('avg_hasil') ?? 0, 2)
                    ],
                    'rendah' => [
                        'count' => $data['rendah']->count(),
                        'avg' => round($data['rendah']->avg('avg_hasil') ?? 0, 2)
                    ],
                ]
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Analisis produktivitas gagal'
            ], 500);
        }
    }

    /**
     * API: Perbandingan tahunan
     */
    public function getYearlyComparison()
    {
        try {
            $years = ImportLog::whereNotNull('tahun')
                ->distinct()
                ->orderBy('tahun')
                ->pluck('tahun');

            $data = $years->map(function ($tahun) {
                $total = DataGulma::whereHas('importLog', function ($q) use ($tahun) {
                    $q->where('tahun', $tahun);
                })->sum('hasil');

                return [
                    'tahun' => $tahun,
                    'total_hasil' => round($total, 2)
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data tahunan'
            ], 500);
        }
    }
}
