<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Data Wilayah di Database ===\n";
$wilayah = DB::table('wilayah')->orderBy('id')->get();

if ($wilayah->count() > 0) {
    foreach ($wilayah as $w) {
        echo "ID: {$w->id}\n";
    }
    echo "\nTotal: " . $wilayah->count() . " wilayah\n";
} else {
    echo "Tidak ada data wilayah!\n";
}
