<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DataGulma;

echo "=== CEK DATA UNTUK POPUP ===\n\n";

// Sample data untuk beberapa wilayah
$wilayahList = [16, 17, 18, 19, 20, 21, 22, 23];

foreach ($wilayahList as $wilayah) {
    echo "WILAYAH $wilayah:\n";
    $data = DataGulma::where('wilayah_id', $wilayah)
        ->limit(3)
        ->get(['seksi', 'kategori', 'wilayah_id', 'pg', 'fm']);
    
    if ($data->count() > 0) {
        foreach ($data as $d) {
            echo "  - Seksi: {$d->seksi} | Kategori: {$d->kategori} | PG: {$d->pg} | FM: {$d->fm}\n";
        }
        echo "  Total records: " . DataGulma::where('wilayah_id', $wilayah)->count() . "\n";
    } else {
        echo "  Tidak ada data\n";
    }
    echo "\n";
}

echo "=== SELESAI ===\n";
