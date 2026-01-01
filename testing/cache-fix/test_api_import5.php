<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\DataGulma;

// Test import_id 5
$importId = 5;
$wilayahNum = 16;

$data = DataGulma::where('wilayah_id', $wilayahNum)->where('import_log_id', $importId)->get();
echo "Records for wilayah 16 with import_id 5: " . $data->count() . "\n";

$kategoriDist = $data->groupBy('kategori')->map(function($g) { return $g->count(); });
foreach ($kategoriDist as $k => $c) {
    echo "  $k: $c\n";
}
?>
