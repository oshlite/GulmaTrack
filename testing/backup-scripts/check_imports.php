<?php
// Simple comparison of import counts
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

use App\Models\ImportLog;
use App\Models\DataGulma;

$imports = ImportLog::orderBy('id')->get();

echo "=== Import Data Verification ===\n\n";

foreach ($imports as $imp) {
    $actualCount = DataGulma::where('import_log_id', $imp->id)->count();
    $status = ($imp->jumlah_berhasil == $actualCount) ? '✓' : '✗ MISMATCH';
    
    echo "Import {$imp->id}: {$imp->nama_file} ({$imp->tahun}/{$imp->bulan}/W{$imp->minggu})\n";
    echo "  Claimed: {$imp->jumlah_berhasil} | Actual: {$actualCount} | {$status}\n\n";
}
