<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    Illuminate\Http\Request::capture()
);

use App\Models\DataGulma;
use App\Models\ImportLog;

echo "=== Sample seksi dari database untuk import 5, wilayah 23 ===\n";
$data = DataGulma::where('import_log_id', 5)->where('wilayah_id', 23)->limit(10)->get(['seksi', 'kategori']);
foreach ($data as $d) {
    echo "Seksi: '{$d->seksi}' | Kategori: '{$d->kategori}'\n";
}

echo "\n=== Check apakah '554A3' ada di database ===\n";
$found = DataGulma::where('seksi', '554A3')->where('wilayah_id', 23)->first();
if ($found) {
    echo "✓ FOUND! Kategori: {$found->kategori}\n";
} else {
    echo "✗ NOT FOUND\n";
    // Check similar
    $similar = DataGulma::where('wilayah_id', 23)->where('seksi', 'LIKE', '554%')->limit(5)->get(['seksi']);
    echo "\nSimilar seksi:\n";
    foreach ($similar as $s) {
        echo "  - {$s->seksi}\n";
    }
}
