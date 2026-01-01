<?php
// Test the API endpoint with import_id parameter

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\DataGulma;

// Test with import_id 7 (gulma.csv)
$importId = 7;
$wilayahNum = 16;

// Simulate the API query
$query = DataGulma::where('wilayah_id', $wilayahNum);

// Priority 1: If specific import_id is provided, use it
if ($importId) {
    echo "Testing with import_id: {$importId}\n";
    $allCount = DataGulma::where('wilayah_id', $wilayahNum)->count();
    echo "Total records for wilayah {$wilayahNum}: {$allCount}\n";
    
    $query->where('import_log_id', $importId);
    $count = $query->count();
    echo "Records for wilayah {$wilayahNum} with import_id {$importId}: {$count}\n";
    
    $data = $query->get();
    
    // Check kategori distribution
    $kategoriDist = $data->groupBy('kategori')->map(function($group) {
        return $group->count();
    });
    
    echo "\nKategori distribution:\n";
    foreach ($kategoriDist as $kategori => $count) {
        echo "  {$kategori}: {$count}\n";
    }
    
    // Show first few records
    echo "\nFirst 5 records:\n";
    $data->take(5)->each(function($item) {
        echo "  Seksi: {$item->seksi}, Kategori: {$item->kategori}, Import: {$item->import_log_id}\n";
    });
}
?>
