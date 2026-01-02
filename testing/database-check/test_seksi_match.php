<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Checking Seksi Matching for Import 12, Wilayah 16 ===\n\n";

// Get seksi from database
$dbSeksi = \App\Models\DataGulma::where('import_log_id', 12)
    ->where('wilayah_id', 16)
    ->pluck('seksi')
    ->sort()
    ->values()
    ->toArray();

echo "Database seksi values (" . count($dbSeksi) . " total):\n";
echo implode(', ', $dbSeksi) . "\n\n";

// Get seksi from GeoJSON
$geojson = json_decode(file_get_contents('dataya/Wil16.geojson'), true);
$geoSeksi = [];
foreach ($geojson['features'] as $feature) {
    if (isset($feature['properties']['Lokasi'])) {
        $geoSeksi[] = $feature['properties']['Lokasi'];
    }
}
sort($geoSeksi);

echo "GeoJSON Lokasi values (" . count($geoSeksi) . " total):\n";
echo implode(', ', array_slice($geoSeksi, 0, 30)) . "...\n\n";

// Check for matches
$matches = array_intersect($dbSeksi, $geoSeksi);
echo "Matching seksi: " . count($matches) . "\n";
if (count($matches) > 0) {
    echo "Matches: " . implode(', ', $matches) . "\n";
} else {
    echo "❌ NO MATCHES FOUND!\n";
    echo "\nThis is why features show as 'Belum Dimonitoring'!\n";
}
