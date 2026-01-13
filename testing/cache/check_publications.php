<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MapPublication;

echo "📚 MapPublications in database:\n";
echo "=".str_repeat("=", 50)."\n";

$pubs = MapPublication::with('importLog')
    ->orderBy('id', 'desc')
    ->get();

foreach ($pubs as $pub) {
    echo "Pub ID {$pub->id}: Import_log_id = {$pub->import_log_id}\n";
    if ($pub->importLog) {
        echo "  └─ File: {$pub->importLog->nama_file}\n";
        echo "  └─ Period: {$pub->importLog->tahun}/{$pub->importLog->bulan}/w{$pub->importLog->minggu}\n";
    }
}
