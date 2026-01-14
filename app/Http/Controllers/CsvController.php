<?php

namespace App\Http\Controllers;

use App\Models\DataGulma;
use App\Models\MapPublication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvController extends Controller
{
    /**
     * Export data Gulma ke CSV dengan format:
     * PG,FM,WIL,SEKSI,NETO,HASIL,UMUR_TNM,TNM_STS,ACTIVITAS,KATEGORI,TANGGAL,TK/HA,TOTAL_TK
     */
    public function export(Request $request)
    {
        // Get data dari publikasi terbaru atau filter tertentu
        $query = DataGulma::query();

        // Filter berdasarkan publish ID jika ada
        if ($request->has('publish_id') && $request->publish_id) {
            $publish = MapPublication::findOrFail($request->publish_id);
            $query->where('import_log_id', $publish->import_log_id);
        } else {
            // Gunakan publikasi terbaru
            $published = MapPublication::getLatestPublished();
            if ($published && $published->importLog) {
                $query->where('import_log_id', $published->import_log_id);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data publikasi untuk di-export'
                ], 404);
            }
        }

        // Filter wilayah jika ada
        if ($request->has('wilayah_id') && $request->wilayah_id) {
            $query->where('wilayah_id', $request->wilayah_id);
        }

        // Filter kategori jika ada
        if ($request->has('kategori') && $request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        // Ambil data
        $data = $query->orderBy('wilayah_id')
            ->orderBy('seksi')
            ->orderBy('tanggal')
            ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data untuk di-export'
            ], 404);
        }

        // Header CSV
        $headers = [
            'PG',
            'FM',
            'WIL',
            'SEKSI',
            'NETO',
            'HASIL',
            'UMUR_TNM',
            'TNM_STS',
            'ACTIVITAS',
            'KATEGORI',
            'TANGGAL',
            'TK/HA',
            'TOTAL_TK'
        ];

        // Generate CSV
        $filename = 'gulma_' . date('Y-m-d_H-i-s') . '.csv';
        
        $response = new StreamedResponse(function () use ($data, $headers) {
            $handle = fopen('php://output', 'w');
            
            // Write UTF-8 BOM untuk compatibility dengan Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Write headers
            fputcsv($handle, $headers);
            
            // Write data rows
            foreach ($data as $row) {
                fputcsv($handle, [
                    $row->pg ?? '',
                    $row->fm ?? '',
                    $row->wilayah_id ?? '',
                    $row->seksi ?? '',
                    $row->neto ?? '',
                    $row->hasil ?? '',
                    $row->umur ?? '',
                    $row->tnm_sts ?? '', // Bisa kosong
                    $row->activitas ?? '',
                    $row->kategori ?? '',
                    $row->tanggal?->format('Y-m-d') ?? '',
                    $row->tk_ha ?? '',
                    $row->total_tk ?? ''
                ]);
            }
            
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', "attachment; filename=\"{$filename}\"");

        return $response;
    }

    /**
     * Get data Gulma sebagai JSON untuk ditampilkan di table
     * Format untuk wilayah.blade.php dan statistik.blade.php
     */
    public function getData(Request $request)
    {
        $query = DataGulma::with('wilayah:id,nama');

        // Filter berdasarkan publish ID jika ada
        if ($request->has('publish_id') && $request->publish_id) {
            $publish = MapPublication::findOrFail($request->publish_id);
            $query->where('import_log_id', $publish->import_log_id);
        } else {
            // Gunakan publikasi terbaru
            $published = MapPublication::getLatestPublished();
            if ($published && $published->importLog) {
                $query->where('import_log_id', $published->import_log_id);
            }
        }

        // Filter wilayah
        if ($request->has('wilayah_id') && $request->wilayah_id) {
            $query->where('wilayah_id', $request->wilayah_id);
        }

        // Filter kategori
        if ($request->has('kategori') && $request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        // Filter aktivitas
        if ($request->has('activitas') && $request->activitas) {
            $query->where('activitas', $request->activitas);
        }

        // Filter tanggal
        if ($request->has('from_date') && $request->from_date) {
            $query->where('tanggal', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->where('tanggal', '<=', $request->to_date);
        }

        // Pagination
        $perPage = (int)($request->input('per_page', 20));
        if ($perPage > 500) $perPage = 500; // Limit max
        
        $data = $query->orderBy('wilayah_id')
            ->orderBy('seksi')
            ->orderBy('tanggal')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data->items(),
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ]
        ]);
    }

    /**
     * Get statistik ringkasan untuk dashboard
     */
    public function getStatistik(Request $request)
    {
        $query = DataGulma::query();

        // Filter berdasarkan publish ID
        if ($request->has('publish_id') && $request->publish_id) {
            $publish = MapPublication::findOrFail($request->publish_id);
            $query->where('import_log_id', $publish->import_log_id);
        } else {
            $published = MapPublication::getLatestPublished();
            if ($published && $published->importLog) {
                $query->where('import_log_id', $published->import_log_id);
            }
        }

        // Filter wilayah
        if ($request->has('wilayah_id') && $request->wilayah_id) {
            $query->where('wilayah_id', $request->wilayah_id);
        }

        // Statistik berdasarkan kategori
        $byKategori = $query->clone()
            ->select('kategori', \DB::raw('count(*) as total'))
            ->whereNotNull('kategori')
            ->groupBy('kategori')
            ->get()
            ->toArray();

        // Statistik berdasarkan aktivitas
        $byActivitas = $query->clone()
            ->select('activitas', \DB::raw('count(*) as total'))
            ->whereNotNull('activitas')
            ->groupBy('activitas')
            ->get()
            ->toArray();

        // Rata-rata nilai
        $stats = $query->clone()
            ->selectRaw('
                COUNT(*) as total_records,
                COUNT(DISTINCT wilayah_id) as total_wilayah,
                COUNT(DISTINCT seksi) as total_lahan,
                AVG(neto) as avg_neto,
                SUM(hasil) as total_hasil,
                AVG(umur) as avg_umur,
                AVG(tk_ha) as avg_tk_ha,
                SUM(total_tk) as total_tenaga_kerja,
                COUNT(CASE WHEN tnm_sts IS NOT NULL THEN 1 END) as dengan_tnm_sts
            ')
            ->first();

        return response()->json([
            'success' => true,
            'statistik' => [
                'total_records' => $stats->total_records ?? 0,
                'total_wilayah' => $stats->total_wilayah ?? 0,
                'total_lahan' => $stats->total_lahan ?? 0,
                'avg_neto' => round($stats->avg_neto ?? 0, 2),
                'total_hasil' => round($stats->total_hasil ?? 0, 2),
                'avg_umur' => round($stats->avg_umur ?? 0, 2),
                'avg_tk_ha' => round($stats->avg_tk_ha ?? 0, 2),
                'total_tenaga_kerja' => round($stats->total_tenaga_kerja ?? 0, 2),
                'dengan_tnm_sts' => $stats->dengan_tnm_sts ?? 0,
            ],
            'by_kategori' => $byKategori,
            'by_activitas' => $byActivitas,
        ]);
    }

    /**
     * Get daftar kategori unik untuk filter
     */
    public function getKategoriList(Request $request)
    {
        $query = DataGulma::query();

        if ($request->has('publish_id') && $request->publish_id) {
            $publish = MapPublication::findOrFail($request->publish_id);
            $query->where('import_log_id', $publish->import_log_id);
        } else {
            $published = MapPublication::getLatestPublished();
            if ($published && $published->importLog) {
                $query->where('import_log_id', $published->import_log_id);
            }
        }

        $kategori = $query->distinct()
            ->whereNotNull('kategori')
            ->pluck('kategori')
            ->sort()
            ->values()
            ->toArray();

        return response()->json([
            'success' => true,
            'kategori' => $kategori
        ]);
    }

    /**
     * Get daftar aktivitas unik untuk filter
     */
    public function getActivitasList(Request $request)
    {
        $query = DataGulma::query();

        if ($request->has('publish_id') && $request->publish_id) {
            $publish = MapPublication::findOrFail($request->publish_id);
            $query->where('import_log_id', $publish->import_log_id);
        } else {
            $published = MapPublication::getLatestPublished();
            if ($published && $published->importLog) {
                $query->where('import_log_id', $published->import_log_id);
            }
        }

        $activitas = $query->distinct()
            ->whereNotNull('activitas')
            ->pluck('activitas')
            ->sort()
            ->values()
            ->toArray();

        return response()->json([
            'success' => true,
            'activitas' => $activitas
        ]);
    }
}
