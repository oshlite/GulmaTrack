<?php
require 'bootstrap/app.php';
app()->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DataGulma;
use App\Models\ImportLog;

echo "=== CHECKING CURRENT DATA ===\n";

$import = ImportLog::where('tahun', 2025)
    ->where('bulan', 12)
    ->where('minggu', 4)
    ->latest()
    ->first();

if ($import) {
    echo "Found import_log_id: {$import->id}\n";
    
    $wil16 = DataGulma::where('import_log_id', $import->id)
        ->where('wilayah_id', 16)
        ->get();
    
    echo "Wilayah 16 count: {$wil16->count()}\n";
    echo "Total TK sum: " . $wil16->sum('total_tk') . "\n";
    echo "\nFirst 5 records:\n";
    
    $wil16->take(5)->each(function($r) {
        echo "  Seksi: {$r->seksi}, TK: {$r->total_tk}, Neto: {$r->neto}\n";
    });
} else {
    echo "No import found for 2025/12/minggu4\n";
}

echo "\n=== ALL DATA ===\n";
$all = DataGulma::where('wilayah_id', 16)->limit(1)->first();
if ($all) {
    echo "Sample record: " . $all->toJson() . "\n";
}
