<?php

namespace App\Http\Controllers;

use App\Models\DataGulma;
use App\Models\ImportLog;
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
            $query = DataGulma::select(
                'wilayah_id',
                DB::raw('COUNT(DISTINCT id_feature) AS total_features'),
                DB::raw('SUM(neto) AS total_neto'),
                DB::raw('SUM(hasil) AS total_hasil'),
                DB::raw('AVG(hasil) AS avg_hasil'),
                DB::raw('AVG(umur_tanaman) AS avg_umur'),
                DB::raw('SUM(total_tk) AS total_tenaga_kerja')
            )
            ->groupBy('wilayah_id')
            ->orderBy('wilayah_id');

            $this->applyPeriodFilter($query, $request);

            $data = $query->get();

            return response()->json([
                'success' => true,
                'data' => $data,
                'filters' => $request->only(['tahun', 'bulan', 'minggu'])
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Query statistik gagal'
            ], 500);
        }
    }

    /**
     * API: Ranking wilayah
     */
    public function getStatistikRanking(Request $request)
    {
        try {
            $query = DataGulma::select(
                'wilayah_id',
                DB::raw('SUM(hasil) AS total_hasil'),
                DB::raw('AVG(hasil) AS avg_hasil'),
                DB::raw('COUNT(DISTINCT id_feature) AS jumlah_features'),
                DB::raw('SUM(neto) AS total_neto')
            )
            ->groupBy('wilayah_id')
            ->orderByDesc('total_hasil');

            $this->applyPeriodFilter($query, $request);

            return response()->json([
                'success' => true,
                'data' => $query->get()
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil ranking'
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
