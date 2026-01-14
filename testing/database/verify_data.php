<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\DataGulma;

echo "=== DATA WILAYAH 16 ===\n";
$data = DataGulma::where('wilayah_id', 16)->get();

foreach ($data as $row) {
    echo $row->seksi . ": " . $row->total_tk . " TK\n";
}

echo "\n=== TOTAL WILAYAH 16 ===\n";
$total = DataGulma::where('wilayah_id', 16)->sum('total_tk');
echo "Total: " . number_format($total, 2, ',', '.') . " Orang\n";

echo "\n=== QUERY TEST (from GulmaController) ===\n";
$result = DB::table('data_gulma')
    ->where('wilayah_id', 16)
    ->selectRaw('SUM(CAST(total_tk AS DECIMAL(10,2))) as total_tenaga_kerja')
    ->first();

echo "Raw query result: " . json_encode($result) . "\n";
echo "Total TK: " . ($result->total_tenaga_kerja ?? '0') . "\n";
