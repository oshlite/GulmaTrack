<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== STRUKTUR TABEL data_gulma ===\n\n";

$columns = DB::select("SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'data_gulma' ORDER BY ORDINAL_POSITION");

foreach ($columns as $col) {
    $null = $col->IS_NULLABLE === 'YES' ? 'nullable' : 'required';
    echo str_pad($col->COLUMN_NAME, 30) . " | " . str_pad($col->DATA_TYPE, 20) . " | $null\n";
}

echo "\n=== SAMPLE DATA (LIMIT 1) ===\n\n";
$sample = DB::table('data_gulma')->first();
if ($sample) {
    foreach ((array)$sample as $key => $value) {
        echo "$key: " . ($value ?? 'NULL') . "\n";
    }
} else {
    echo "Tidak ada data di tabel data_gulma\n";
}
