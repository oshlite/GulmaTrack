<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\DataGulma;
use App\Models\ImportLog;
use App\Models\MapPublication;

echo "=== Import Logs Tahun 2025, Bulan 12, Minggu 4 ===\n\n";

$imports = ImportLog::where('tahun', 2025)
    ->where('bulan', 12)
    ->where('minggu', 4)
    ->orderBy('id', 'desc')
    ->get();

foreach ($imports as $imp) {
    $count = DataGulma::where('import_log_id', $imp->id)->count();
    echo "ID: {$imp->id} | File: {$imp->nama_file} | Status: {$imp->status} | Total Data: {$count}\n";
}

echo "\n\n=== Published Map ===\n";
$pub = MapPublication::getLatestPublished();
if ($pub) {
    echo "Import ID: {$pub->import_log_id}\n";
    $count = DataGulma::where('import_log_id', $pub->import_log_id)->count();
    echo "Total Data: {$count}\n";
    
    // Per wilayah
    echo "\nPer Wilayah:\n";
    for ($wil = 16; $wil <= 23; $wil++) {
        $c = DataGulma::where('import_log_id', $pub->import_log_id)->where('wilayah_id', $wil)->count();
        echo "  Wilayah $wil: $c\n";
    }
}
