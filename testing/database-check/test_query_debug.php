<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Debugging Import 12 Wilayah 16 Query ===\n\n";

// Test with proper types
$importId = 12;
$wilayahId = 16;

echo "Testing query with import_log_id = $importId (type: " . gettype($importId) . ")\n";
echo "Testing query with wilayah_id = $wilayahId\n\n";

$query = \App\Models\DataGulma::where('wilayah_id', $wilayahId);
echo "Before import_log_id filter: " . $query->count() . " records\n";

$query->where('import_log_id', $importId);
echo "After import_log_id = $importId filter: " . $query->count() . " records\n\n";

// Try with string too
$query2 = \App\Models\DataGulma::where('wilayah_id', $wilayahId);
$query2->where('import_log_id', '12');
echo "After import_log_id = '12' (string) filter: " . $query2->count() . " records\n\n";

// Get actual records
$records = \App\Models\DataGulma::where('wilayah_id', $wilayahId)
    ->where('import_log_id', $importId)
    ->get();

echo "Sample records with kategori:\n";
foreach ($records as $r) {
    echo "  - Seksi: {$r->seksi}, Kategori: {$r->kategori}, PG: {$r->pg}\n";
}
