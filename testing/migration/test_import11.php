<?php
require 'bootstrap/app.php';

$app = resolve('Illuminate\Contracts\Console\Kernel')->bootstrap();

$importLog = \App\Models\ImportLog::find(11);

if ($importLog) {
    $dataCount = \App\Models\DataGulma::where('import_log_id', 11)->count();
    $wilayahs = \App\Models\DataGulma::where('import_log_id', 11)
        ->pluck('wilayah_id')
        ->unique()
        ->values()
        ->all();
    
    echo json_encode([
        'import_id' => $importLog->id,
        'nama_file' => $importLog->nama_file,
        'tahun' => $importLog->tahun,
        'bulan' => $importLog->bulan,
        'minggu' => $importLog->minggu,
        'status' => $importLog->status,
        'total_records' => $dataCount,
        'wilayahs' => $wilayahs,
        'sample_data' => \App\Models\DataGulma::where('import_log_id', 11)->first()?->toArray()
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} else {
    echo json_encode(['error' => 'Import log 11 not found']);
}
