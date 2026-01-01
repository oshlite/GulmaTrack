<?php

use Illuminate\Support\Facades\Route;

// Debug endpoint untuk test import_id parameter
Route::get('/debug/check-import/{wilayahNum}/{importId}', function ($wilayahNum, $importId) {
    $count = \App\Models\DataGulma::where('wilayah_id', $wilayahNum)
        ->where('import_log_id', $importId)
        ->count();
    
    $importLog = \App\Models\ImportLog::find($importId);
    
    $allCount = \App\Models\DataGulma::where('wilayah_id', $wilayahNum)->count();
    
    return [
        'wilayah' => $wilayahNum,
        'import_id' => $importId,
        'import_log' => $importLog ? [
            'id' => $importLog->id,
            'nama_file' => $importLog->nama_file,
            'wilayah_id' => $importLog->wilayah_id,
        ] : null,
        'records_with_this_import' => $count,
        'total_records_for_wilayah' => $allCount,
    ];
});

// Debug endpoint untuk lihat semua import logs
Route::get('/debug/import-logs', function () {
    $logs = \App\Models\ImportLog::orderBy('id', 'desc')->limit(10)->get();
    return $logs->map(function($log) {
        $count = \App\Models\DataGulma::where('import_log_id', $log->id)->count();
        return [
            'id' => $log->id,
            'nama_file' => $log->nama_file,
            'wilayah_id' => $log->wilayah_id,
            'tahun' => $log->tahun,
            'bulan' => $log->bulan,
            'minggu' => $log->minggu,
            'status' => $log->status,
            'data_gulma_count' => $count,
            'jumlah_records' => $log->jumlah_records,
        ];
    });
});

// Debug endpoint untuk test API response langsung
Route::get('/debug/test-api/{wilayahNum}/{importId}', function ($wilayahNum, $importId) {
    $gulmaData = \App\Models\DataGulma::where('wilayah_id', $wilayahNum)
        ->where('import_log_id', $importId)
        ->get(['seksi', 'kategori', 'import_log_id']);
    
    $importLog = \App\Models\ImportLog::find($importId);
    
    $allByWilayah = \App\Models\DataGulma::where('wilayah_id', $wilayahNum)
        ->with('importLog:id,nama_file')
        ->get(['seksi', 'kategori', 'import_log_id']);
    
    return [
        'query_result' => [
            'wilayah_id' => $wilayahNum,
            'import_log_id' => $importId,
            'records_found' => $gulmaData->count(),
            'kategori_list' => $gulmaData->pluck('kategori')->unique()->toArray(),
            'sample_data' => $gulmaData->take(5)->toArray(),
        ],
        'import_log_info' => $importLog ? [
            'id' => $importLog->id,
            'nama_file' => $importLog->nama_file,
            'jumlah_records' => $importLog->jumlah_records,
            'tahun' => $importLog->tahun,
            'bulan' => $importLog->bulan,
            'minggu' => $importLog->minggu,
        ] : 'NOT FOUND',
        'all_data_for_wilayah_by_import' => $allByWilayah->groupBy('import_log_id')->map(function($group, $importId) {
            $importLog = \App\Models\ImportLog::find($importId);
            return [
                'import_id' => $importId,
                'file' => $importLog ? $importLog->nama_file : 'unknown',
                'record_count' => $group->count(),
                'kategori' => $group->pluck('kategori')->unique()->toArray(),
            ];
        })->values()->toArray(),
    ];
});

