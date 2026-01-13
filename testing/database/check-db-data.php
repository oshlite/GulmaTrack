<?php
require_once __DIR__ . '/../bootstrap/app.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Get database data for Wilayah 16
$dbData = DB::table('data_gulma')->where('wilayah', 16)->get();

echo "=== DATABASE DATA ===\n";
echo "Total records: " . $dbData->count() . "\n";

// Count by kategori
$bersih = $dbData->where('kategori', 'Bersih')->count();
$ringan = $dbData->where('kategori', 'Ringan')->count();

echo "Bersih: $bersih\n";
echo "Ringan: $ringan\n";

// Count TK
$totalTK = $dbData->sum('tk_ha');
$totalNeto = $dbData->sum('neto');

echo "Total TK: " . number_format($totalTK, 2) . "\n";
echo "Total Neto: " . number_format($totalNeto, 2) . "\n";
