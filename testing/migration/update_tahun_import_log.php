<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ImportLog;

echo "=== UPDATE TAHUN DI IMPORT LOGS ===\n\n";

// Update semua import log yang belum punya tahun
$imports = ImportLog::whereNull('tahun')->get();

echo "Ditemukan {$imports->count()} import log tanpa tahun\n\n";

foreach ($imports as $import) {
    // Set tahun default berdasarkan created_at atau 2025
    $tahun = $import->created_at ? $import->created_at->year : 2025;
    
    $import->tahun = $tahun;
    $import->save();
    
    echo "✓ Import #{$import->id} - {$import->nama_file} -> Tahun: {$tahun}\n";
}

echo "\n=== SELESAI ===\n";
echo "Total diupdate: {$imports->count()} records\n";
