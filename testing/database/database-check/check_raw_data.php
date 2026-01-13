<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DataGulma;

echo "=== CEK RAW DATA WILAYAH 17 ===\n\n";

$data = DataGulma::where('wilayah_id', 17)->first();

if ($data) {
    print_r($data->toArray());
} else {
    echo "Tidak ada data\n";
}

echo "\n=== SELESAI ===\n";
