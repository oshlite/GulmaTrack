<?php

require __DIR__ . '/bootstrap/app.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DataGulma;

echo "=== WILAYAH 16 - IMPORT 55 vs 56 ===\n\n";

// Import 55 data
$imp55 = DataGulma::where('import_log_id', 55)->where('wilayah_id', 16)->get();
echo "IMPORT 55 (2026 Jan W3):\n";
echo "  Records: " . $imp55->count() . "\n";
echo "  Total TK: " . $imp55->sum('total_tk') . "\n";
echo "  Total Neto: " . $imp55->sum('neto') . "\n";
echo "  Sample:\n";
foreach ($imp55->take(3) as $r) {
    echo "    - {$r->seksi} | TK={$r->total_tk} | Neto={$r->neto} | Kat={$r->kategori}\n";
}

echo "\n";

// Import 56 data
$imp56 = DataGulma::where('import_log_id', 56)->where('wilayah_id', 16)->get();
echo "IMPORT 56 (2025 Nov W1 Re-upload):\n";
echo "  Records: " . $imp56->count() . "\n";
echo "  Total TK: " . $imp56->sum('total_tk') . "\n";
echo "  Total Neto: " . $imp56->sum('neto') . "\n";
echo "  Sample:\n";
foreach ($imp56->take(3) as $r) {
    echo "    - {$r->seksi} | TK={$r->total_tk} | Neto={$r->neto} | Kat={$r->kategori}\n";
}

echo "\n=== COMPARISON ===\n";
echo "Import 55: " . number_format($imp55->sum('total_tk'), 2) . " TK (expected 414, but incomplete)\n";
echo "Import 56: " . number_format($imp56->sum('total_tk'), 2) . " TK (CORRECT)\n";
?>
