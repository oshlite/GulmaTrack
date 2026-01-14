<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\DataGulma;

header('Content-Type: application/json');

// Simulate getStatistikSummary() response
$data = DB::table('data_gulma')
    ->selectRaw('wilayah_id, COUNT(*) as total_features, SUM(CAST(neto AS DECIMAL(10,2))) as total_neto, SUM(CAST(hasil AS DECIMAL(10,2))) as total_hasil, SUM(CAST(total_tk AS DECIMAL(10,2))) as total_tenaga_kerja')
    ->whereNotNull('wilayah_id')
    ->groupBy('wilayah_id')
    ->orderBy('wilayah_id')
    ->get();

// Cast ke float untuk JSON
$response = $data->map(function($item) {
    return [
        'wilayah_id' => (int) $item->wilayah_id,
        'total_features' => (int) $item->total_features,
        'total_neto' => (float) $item->total_neto,
        'total_hasil' => (float) $item->total_hasil,
        'total_tenaga_kerja' => (float) $item->total_tenaga_kerja,
    ];
})->toArray();

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
