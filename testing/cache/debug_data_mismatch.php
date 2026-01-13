<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    Illuminate\Http\Request::capture()
);

use App\Models\ImportLog;
use App\Models\DataGulma;
use App\Models\MapPublication;

echo "=== DEBUGGING: Check data untuk kedua file ===\n\n";

// Import #7 - gulma.csv
echo "IMPORT #7 (gulma.csv):\n";
$import7 = ImportLog::find(7);
if ($import7) {
    $count = DataGulma::where('import_log_id', 7)->count();
    echo "  Status: {$import7->status}\n";
    echo "  Records: {$count}\n";
    
    // Count per wilayah
    for ($wil = 16; $wil <= 23; $wil++) {
        $c = DataGulma::where('import_log_id', 7)->where('wilayah_id', $wil)->count();
        if ($c > 0) echo "    Wilayah $wil: $c\n";
    }
    
    // Check kategori distribution
    $categories = DataGulma::where('import_log_id', 7)
        ->groupBy('kategori')
        ->selectRaw('kategori, count(*) as cnt')
        ->get();
    
    echo "  Kategori distribution:\n";
    foreach ($categories as $cat) {
        echo "    {$cat->kategori}: {$cat->cnt}\n";
    }
}

echo "\n\nIMPORT #5 (Minggu4 (1).csv):\n";
$import5 = ImportLog::find(5);
if ($import5) {
    $count = DataGulma::where('import_log_id', 5)->count();
    echo "  Status: {$import5->status}\n";
    echo "  Records: {$count}\n";
    
    // Count per wilayah
    for ($wil = 16; $wil <= 23; $wil++) {
        $c = DataGulma::where('import_log_id', 5)->where('wilayah_id', $wil)->count();
        if ($c > 0) echo "    Wilayah $wil: $c\n";
    }
    
    // Check kategori distribution
    $categories = DataGulma::where('import_log_id', 5)
        ->groupBy('kategori')
        ->selectRaw('kategori, count(*) as cnt')
        ->get();
    
    echo "  Kategori distribution:\n";
    foreach ($categories as $cat) {
        echo "    {$cat->kategori}: {$cat->cnt}\n";
    }
}

echo "\n\nPUBLICATION STATUS:\n";
$pub = MapPublication::where('tahun', 2025)->where('bulan', 12)->where('minggu', 3)->where('status', 'published')->first();
if ($pub) {
    echo "Published Import ID: {$pub->import_log_id}\n";
    echo "Nama File: {$pub->importLog->nama_file}\n";
} else {
    echo "No published record found for 2025/12/W3\n";
}
