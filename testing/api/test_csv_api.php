<?php
// Test CSV API endpoints

require_once 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';

use Illuminate\Http\Request;
use App\Models\DataGulma;
use App\Models\MapPublication;

// Simple test to verify data exists
echo "Testing CSV API endpoints...\n";
echo "==============================\n\n";

// Test 1: Check if DataGulma table has data
$count = DataGulma::count();
echo "1. Total DataGulma records: {$count}\n";

// Test 2: Check latest published
$published = MapPublication::getLatestPublished();
if ($published) {
    echo "2. Latest published: Import ID {$published->import_log_id}\n";
    $dataCount = DataGulma::where('import_log_id', $published->import_log_id)->count();
    echo "   Total records in publication: {$dataCount}\n";
} else {
    echo "2. No published data found\n";
}

// Test 3: Check CSV columns
$sample = DataGulma::first();
if ($sample) {
    echo "3. Sample CSV data:\n";
    echo "   PG: {$sample->pg}\n";
    echo "   FM: {$sample->fm}\n";
    echo "   Seksi: {$sample->seksi}\n";
    echo "   Neto: {$sample->neto}\n";
    echo "   Hasil: {$sample->hasil}\n";
    echo "   Umur: {$sample->umur}\n";
    echo "   TNM STS: {$sample->tnm_sts}\n";
    echo "   Aktivitas: {$sample->activitas}\n";
    echo "   Kategori: {$sample->kategori}\n";
    echo "   Tanggal: {$sample->tanggal}\n";
    echo "   TK/HA: {$sample->tk_ha}\n";
    echo "   Total TK: {$sample->total_tk}\n";
} else {
    echo "3. No sample data found\n";
}

echo "\n✅ CSV API test complete\n";
