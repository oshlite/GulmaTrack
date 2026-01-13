<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ImportLog;

echo "=== UPDATE IMPORT LOG LAMA ===\n\n";

// Update import log yang wilayah_id masih "16" padahal sebenarnya multiple wilayah
$import = ImportLog::where('nama_file', 'gulma.csv')
    ->where('status', 'success')
    ->latest()
    ->first();

if ($import) {
    echo "Import ditemukan:\n";
    echo "- File: {$import->nama_file}\n";
    echo "- Wilayah lama: {$import->wilayah_id}\n";
    echo "- Records: {$import->jumlah_berhasil}\n\n";
    
    // Update ke multiple wilayah
    $import->wilayah_id = '16,17,18,19,20,21,22,23';
    $import->save();
    
    echo "✓ Berhasil diupdate!\n";
    echo "- Wilayah baru: {$import->wilayah_id}\n";
} else {
    echo "Import log tidak ditemukan\n";
}

echo "\n=== SELESAI ===\n";
