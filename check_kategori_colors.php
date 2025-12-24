<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DataGulma;

echo "=== CEK KATEGORI DATA GULMA ===\n\n";

// Cek kategori apa saja yang ada
$kategoris = DataGulma::whereNotNull('kategori')
    ->distinct()
    ->pluck('kategori')
    ->filter()
    ->sort()
    ->values();

echo "Kategori yang ada di database:\n";
foreach ($kategoris as $kategori) {
    $count = DataGulma::where('kategori', $kategori)->count();
    echo "  - {$kategori}: {$count} records\n";
}

echo "\n=== MAPPING WARNA ===\n";
echo "Bersih  -> Biru (#3498db)\n";
echo "Ringan  -> Hijau (#27ae60)\n";
echo "Sedang  -> Kuning (#f1c40f)\n";
echo "Berat   -> Merah (#e74c3c)\n";
echo "Null/Kosong -> Putih (#ffffff)\n";

echo "\n=== SAMPLE DATA PER WILAYAH ===\n";
$wilayahList = [16, 17, 18, 19, 20, 21, 22, 23];

foreach ($wilayahList as $wilayah) {
    $kategoris = DataGulma::where('wilayah_id', $wilayah)
        ->whereNotNull('kategori')
        ->where('kategori', '!=', '')
        ->selectRaw('kategori, COUNT(*) as count')
        ->groupBy('kategori')
        ->get();
    
    if ($kategoris->count() > 0) {
        echo "\nWilayah {$wilayah}:\n";
        foreach ($kategoris as $k) {
            echo "  {$k->kategori}: {$k->count} seksi\n";
        }
    }
}

echo "\n=== SELESAI ===\n";
