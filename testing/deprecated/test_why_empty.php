<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DataGulma;
use App\Models\ImportLog;

echo "=== DEBUGGING WHY FEATURES ARE EMPTY ===\n\n";

// Check database
$totalRecords = DataGulma::count();
$latestImport = ImportLog::latest('id')->first();

echo "Total records in data_gulma: $totalRecords\n";
echo "Latest import ID: {$latestImport->id}\n";
echo "Latest import created: {$latestImport->created_at}\n\n";

// Check wilayah 16 data
$wil16Total = DataGulma::where('wilayah_id', 16)->count();
echo "Wilayah 16 total records: $wil16Total\n";

// Check if seksi matches
$wil16WithSeksi = DataGulma::where('wilayah_id', 16)
    ->whereNotNull('seksi')
    ->where('seksi', '!=', '')
    ->count();
echo "Wilayah 16 with non-empty seksi: $wil16WithSeksi\n\n";

// Show sample data
$samples = DataGulma::where('wilayah_id', 16)
    ->whereNotNull('kategori')
    ->limit(5)
    ->get(['id', 'seksi', 'kategori', 'pg', 'fm', 'import_log_id']);

echo "Sample data from wilayah 16:\n";
foreach ($samples as $sample) {
    echo sprintf(
        "  ID: %d | Seksi: %s | Kategori: %s | PG: %s | FM: %s | Import: %d\n",
        $sample->id,
        $sample->seksi,
        $sample->kategori,
        $sample->pg,
        $sample->fm,
        $sample->import_log_id
    );
}

// Now check GeoJSON file
echo "\n=== CHECKING GEOJSON FILE ===\n";
$geojsonPath = base_path('dataya/Wil16.geojson');
$geojsonContent = file_get_contents($geojsonPath);
$geojson = json_decode($geojsonContent, true);

echo "GeoJSON features count: " . count($geojson['features']) . "\n";
echo "Sample seksi from GeoJSON:\n";
foreach (array_slice($geojson['features'], 0, 5) as $feature) {
    $seksi = $feature['properties']['SEKSI'] ?? 'N/A';
    echo "  Seksi: $seksi\n";
}

// Now check for matches
echo "\n=== CHECKING FOR SEKSI MATCHES ===\n";
$dbSeksi = DataGulma::where('wilayah_id', 16)
    ->whereNotNull('seksi')
    ->pluck('seksi')
    ->unique()
    ->sort()
    ->values()
    ->toArray();

$geojsonSeksi = array_map(function($f) {
    return $f['properties']['SEKSI'] ?? null;
}, $geojson['features']);
$geojsonSeksi = array_filter(array_unique($geojsonSeksi));
sort($geojsonSeksi);

echo "Database seksi values (" . count($dbSeksi) . "): " . implode(', ', array_slice($dbSeksi, 0, 10)) . "...\n";
echo "GeoJSON seksi values (" . count($geojsonSeksi) . "): " . implode(', ', array_slice($geojsonSeksi, 0, 10)) . "...\n\n";

// Check if they match
$matches = array_intersect($dbSeksi, $geojsonSeksi);
echo "Matching seksi count: " . count($matches) . "\n";
if (count($matches) > 0) {
    echo "Sample matches: " . implode(', ', array_slice($matches, 0, 10)) . "\n";
} else {
    echo "❌ NO MATCHES FOUND! This is the problem!\n";
    echo "\nSample comparison:\n";
    echo "  DB: " . ($dbSeksi[0] ?? 'empty') . " (length: " . strlen($dbSeksi[0] ?? '') . ")\n";
    echo "  GeoJSON: " . ($geojsonSeksi[0] ?? 'empty') . " (length: " . strlen($geojsonSeksi[0] ?? '') . ")\n";
}
