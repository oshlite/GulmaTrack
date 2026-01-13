<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    Illuminate\Http\Request::capture()
);

use App\Models\MapPublication;
use App\Models\ImportLog;

echo "=== Fixing existing publications with periode info ===\n\n";

$publications = MapPublication::where('status', 'published')->get();

echo "Found " . $publications->count() . " published records\n\n";

$fixed = 0;
foreach ($publications as $pub) {
    if ($pub->importLog) {
        $pub->update([
            'tahun' => $pub->importLog->tahun,
            'bulan' => $pub->importLog->bulan,
            'minggu' => $pub->importLog->minggu
        ]);
        
        echo "✓ Fixed publication ID {$pub->id} - Import ID {$pub->import_log_id} ({$pub->importLog->tahun}/{$pub->importLog->bulan}/W{$pub->importLog->minggu})\n";
        $fixed++;
    } else {
        echo "✗ Publication ID {$pub->id} - Import log tidak ditemukan!\n";
    }
}

echo "\n✅ Fixed {$fixed} publications\n";
