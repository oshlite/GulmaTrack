<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DataGulma;
use App\Models\ImportLog;

echo "=== CEK DATA WILAYAH ===\n\n";

// Cek data gulma per wilayah
echo "Data Gulma per Wilayah:\n";
$dataPerWilayah = DataGulma::selectRaw('wilayah_id, COUNT(*) as total')
    ->groupBy('wilayah_id')
    ->orderBy('wilayah_id')
    ->get();

foreach ($dataPerWilayah as $data) {
    echo "Wilayah {$data->wilayah_id}: {$data->total} records\n";
}

echo "\n=== RIWAYAT IMPORT ===\n";
$imports = ImportLog::latest()->limit(5)->get();
foreach ($imports as $import) {
    echo "\nFile: {$import->nama_file}\n";
    echo "Wilayah: {$import->wilayah_id}\n";
    if ($import->bulan && $import->minggu) {
        echo "Periode: Bulan {$import->bulan}, Minggu {$import->minggu}\n";
    }
    echo "Records: {$import->jumlah_records} | Berhasil: {$import->jumlah_berhasil} | Gagal: {$import->jumlah_gagal}\n";
    echo "Status: {$import->status}\n";
}

echo "\n=== SELESAI ===\n";
