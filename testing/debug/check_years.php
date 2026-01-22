<?php
// Quick script to check what years exist in database
require_once __DIR__ . '/../../bootstrap/app.php';

use App\Models\MapPublication;

$publications = MapPublication::where('status', 'published')
    ->select('tahun')
    ->distinct()
    ->orderBy('tahun', 'desc')
    ->get();

echo "=== TAHUN YANG ADA DI DATABASE ===\n";
foreach ($publications as $pub) {
    echo "Tahun: {$pub->tahun}\n";
}

echo "\n=== FILTER DENGAN 3 TAHUN TERAKHIR ===\n";
$currentYear = 2026;
$minYear = $currentYear - 2; // 3 years = 2026, 2025, 2024
echo "Current Year: {$currentYear}\n";
echo "Min Year (for 3 years): {$minYear}\n";

$filtered = MapPublication::where('status', 'published')
    ->where('tahun', '>=', $minYear)
    ->select('tahun')
    ->distinct()
    ->orderBy('tahun', 'desc')
    ->get();

echo "\nFiltered Tahun:\n";
foreach ($filtered as $pub) {
    echo "- {$pub->tahun}\n";
}

echo "\nTotal records before filter: " . $publications->count() . "\n";
echo "Total records after filter: " . $filtered->count() . "\n";
?>
