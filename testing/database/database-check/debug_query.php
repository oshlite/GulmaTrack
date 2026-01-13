<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Debug Import #29 ===\n";
$import = \App\Models\ImportLog::find(29);
echo "Wilayah ID: " . $import->wilayah_id . "\n";
echo "Type: " . gettype($import->wilayah_id) . "\n";

echo "\n=== Query Test ===\n";
$query1 = \App\Models\DataGulma::where('import_log_id', 29)->where('wilayah_id', 16);
echo "Query 1 (import_log_id=29, wilayah_id=16): " . $query1->count() . " records\n";

$query2 = \App\Models\DataGulma::where('import_log_id', 29);
echo "Query 2 (import_log_id=29): " . $query2->count() . " records\n";

echo "\n=== Sample Data ===\n";
$sample = \App\Models\DataGulma::where('import_log_id', 29)->where('wilayah_id', 16)->limit(3)->get();
foreach ($sample as $data) {
    echo "  - Seksi: {$data->seksi}, Kategori: {$data->kategori}\n";
}

echo "\n=== Test WilayahController Logic ===\n";
// Test the LIKE query
$latestImportLog = \App\Models\ImportLog::where('status', 'success')
    ->where('wilayah_id', 'LIKE', "%16%")
    ->orderBy('created_at', 'desc')
    ->first();
    
if ($latestImportLog) {
    echo "Found import log: #{$latestImportLog->id}\n";
    echo "Wilayah: {$latestImportLog->wilayah_id}\n";
    
    $gulmaData = \App\Models\DataGulma::where('wilayah_id', 16)
        ->where('import_log_id', $latestImportLog->id)
        ->get();
    echo "Data count: " . $gulmaData->count() . "\n";
} else {
    echo "No import log found!\n";
}
?>
