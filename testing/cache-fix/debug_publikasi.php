<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    Illuminate\Http\Request::capture()
);

use App\Models\ImportLog;
use App\Models\MapPublication;

echo "=== Testing /api/admin/files-by-period ===\n\n";

// Test untuk periode 2025/12/4
$tahun = 2025;
$bulan = 12;
$minggu = 4;

echo "Query untuk periode: {$tahun}/{$bulan}/W{$minggu}\n\n";

// Get files
$files = ImportLog::where('tahun', $tahun)
    ->where('bulan', $bulan)
    ->where('minggu', $minggu)
    ->where('status', 'success')
    ->orderBy('created_at', 'desc')
    ->get();

echo "Files ditemukan: " . $files->count() . "\n";

foreach ($files as $import) {
    $publication = MapPublication::where('import_log_id', $import->id)
        ->where('status', 'published')
        ->first();
    
    echo "\n- ID: {$import->id} | File: {$import->nama_file} | Records: {$import->jumlah_berhasil}\n";
    echo "  Published: " . ($publication ? 'YES' : 'NO') . "\n";
}

echo "\n\n=== Test periode 2025/12/3 ===\n\n";

$tahun = 2025;
$bulan = 12;
$minggu = 3;

$files = ImportLog::where('tahun', $tahun)
    ->where('bulan', $bulan)
    ->where('minggu', $minggu)
    ->where('status', 'success')
    ->orderBy('created_at', 'desc')
    ->get();

echo "Files ditemukan: " . $files->count() . "\n";

foreach ($files as $import) {
    $publication = MapPublication::where('import_log_id', $import->id)
        ->where('status', 'published')
        ->first();
    
    echo "\n- ID: {$import->id} | File: {$import->nama_file} | Records: {$import->jumlah_berhasil}\n";
    echo "  Published: " . ($publication ? 'YES' : 'NO') . "\n";
}

echo "\n\n=== Map Publications Current State ===\n";
$pubs = MapPublication::where('status', 'published')->get();
foreach ($pubs as $pub) {
    echo "ID: {$pub->id} | Import ID: {$pub->import_log_id} | Periode: {$pub->tahun}/{$pub->bulan}/W{$pub->minggu} | Published At: {$pub->published_at}\n";
}
