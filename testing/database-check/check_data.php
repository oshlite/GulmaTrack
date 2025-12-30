<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Data Gulma di Database ===\n\n";

$data = DB::table('data_gulma')
    ->orderBy('wilayah_id')
    ->orderBy('id_feature')
    ->limit(20)
    ->get();

if ($data->count() > 0) {
    foreach ($data as $d) {
        echo "Wilayah: {$d->wilayah_id} | SEKSI: {$d->id_feature} | Status: {$d->status_gulma} | Persentase: {$d->persentase}%\n";
    }
    
    $total = DB::table('data_gulma')->count();
    echo "\n=== Summary ===\n";
    echo "Total records: {$total}\n\n";
    
    $byWilayah = DB::table('data_gulma')
        ->select('wilayah_id', DB::raw('count(*) as count'))
        ->groupBy('wilayah_id')
        ->orderBy('wilayah_id')
        ->get();
    
    echo "Per Wilayah:\n";
    foreach ($byWilayah as $w) {
        echo "  Wilayah {$w->wilayah_id}: {$w->count} records\n";
    }
    
    echo "\nStatus Gulma Distribution:\n";
    $byStatus = DB::table('data_gulma')
        ->select('status_gulma', DB::raw('count(*) as count'))
        ->groupBy('status_gulma')
        ->orderBy('count', 'desc')
        ->get();
    
    foreach ($byStatus as $s) {
        echo "  {$s->status_gulma}: {$s->count} records\n";
    }
} else {
    echo "Tidak ada data gulma di database!\n";
}
