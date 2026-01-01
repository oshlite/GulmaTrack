<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\ImportLog;
use App\Models\MapPublication;

echo "=== IMPORT LOGS untuk Tahun 2025, Bulan 12, Minggu 4 ===\n\n";

$imports = ImportLog::where('tahun', 2025)
    ->where('bulan', 12)
    ->where('minggu', 4)
    ->orderBy('id', 'desc')
    ->get(['id', 'nama_file', 'wilayah_id', 'created_at', 'status']);

foreach ($imports as $import) {
    echo "ID: {$import->id} | File: {$import->nama_file} | Wilayah: {$import->wilayah_id} | Created: {$import->created_at} | Status: {$import->status}\n";
    
    // Count data for this import
    $count = \App\Models\DataGulma::where('import_log_id', $import->id)->count();
    echo "   └─ Total Data Gulma: {$count} records\n\n";
}

echo "\n=== PUBLISHED MAP ===\n";
$published = MapPublication::getLatestPublished();
if ($published) {
    echo "Published Import ID: {$published->import_log_id}\n";
    echo "Published At: {$published->published_at}\n";
    echo "Published By: " . ($published->publisher ? $published->publisher->name : 'N/A') . "\n";
    
    $count = \App\Models\DataGulma::where('import_log_id', $published->import_log_id)->count();
    echo "Data Records: {$count}\n";
} else {
    echo "No published map found\n";
}
