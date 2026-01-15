<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DataGulma;

echo "=== DATABASE CHECK ===\n";
echo "Total records: " . DataGulma::count() . "\n\n";

$records = DataGulma::select('pg', 'fm', 'seksi', 'hasil', 'umur', 'tnm_sts', 'activitas', 'tanggal', 'total_tk')
    ->limit(5)
    ->get();

foreach ($records as $i => $record) {
    echo "Record " . ($i + 1) . ":\n";
    echo "  PG: " . ($record->pg ?? 'NULL') . "\n";
    echo "  FM: " . ($record->fm ?? 'NULL') . "\n";
    echo "  HASIL: " . ($record->hasil ?? 'NULL') . "\n";
    echo "  UMUR: " . ($record->umur ?? 'NULL') . "\n";
    echo "  TNM_STS: " . ($record->tnm_sts ?? 'NULL') . "\n";
    echo "  AKTIVITAS: " . ($record->activitas ?? 'NULL') . "\n";
    echo "  TANGGAL: " . ($record->tanggal ?? 'NULL') . "\n";
    echo "  TOTAL_TK: " . ($record->total_tk ?? 'NULL') . "\n";
    echo "\n";
}
