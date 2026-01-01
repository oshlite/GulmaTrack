<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    Illuminate\Http\Request::capture()
);

use App\Models\ImportLog;
use App\Models\MapPublication;

echo "=== Test getPublishedForPeriod ===\n\n";

// Test untuk periode 2025/12/W3
$pub = MapPublication::getPublishedForPeriod(2025, 12, 3);

if ($pub) {
    echo "✓ Found: Import ID {$pub->import_log_id}\n";
    echo "  Nama File: {$pub->importLog->nama_file}\n";
} else {
    echo "✗ Not found\n";
}

echo "\n=== Test Priority 3 Logic ===\n\n";

$wilayah_number = 23;

// Emulate Priority 3 logic for GUEST
$importLog = ImportLog::where('status', 'success')
    ->where('wilayah_id', 'LIKE', "%{$wilayah_number}%")
    ->orderBy('tahun', 'desc')
    ->orderBy('bulan', 'desc')
    ->orderBy('minggu', 'desc')
    ->take(12)
    ->get();

echo "Import logs retrieved: " . $importLog->count() . "\n\n";

$publishedLog = null;
foreach ($importLog as $log) {
    echo "Checking period {$log->tahun}/{$log->bulan}/W{$log->minggu} (Import ID: {$log->id})...\n";
    
    $pub = MapPublication::getPublishedForPeriod($log->tahun, $log->bulan, $log->minggu);
    if ($pub && $pub->importLog) {
        $publishedLog = $pub->importLog;
        echo "  ✓ FOUND! Using import_id {$pub->import_log_id}\n";
        break;
    } else {
        echo "  ✗ Not published\n";
    }
}

if ($publishedLog) {
    echo "\nFinal: Using import_log_id {$publishedLog->id}\n";
} else {
    echo "\nFinal: No published data found\n";
}
