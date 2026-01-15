<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DataGulma;

echo "=== DATABASE SEKSI VALUES FOR WILAYAH 16 ===\n";

$records = DataGulma::where('wilayah_id', 16)
    ->where('import_log_id', 8)
    ->select('seksi')
    ->distinct()
    ->get();

$seksiList = [];
foreach ($records as $record) {
    $seksiList[] = strtolower(trim($record->seksi));
}

sort($seksiList);
echo "Total unique seksi: " . count($seksiList) . "\n";
echo "Seksi values:\n";
foreach ($seksiList as $seksi) {
    echo "  - '$seksi'\n";
}

echo "\n=== GeoJSON Lokasi VALUES ===\n";
$filePath = 'datala/Wil16.geojson';
if (file_exists($filePath)) {
    $geojson = json_decode(file_get_contents($filePath), true);
    $lokasiList = [];
    foreach ($geojson['features'] as $feature) {
        if (isset($feature['properties']['Lokasi'])) {
            $lokasiList[] = strtolower(trim($feature['properties']['Lokasi']));
        }
    }
    $lokasiList = array_unique($lokasiList);
    sort($lokasiList);
    echo "Total unique Lokasi in GeoJSON: " . count($lokasiList) . "\n";
    echo "Lokasi values (first 20):\n";
    for ($i = 0; $i < min(20, count($lokasiList)); $i++) {
        echo "  - '" . $lokasiList[$i] . "'\n";
    }
    
    echo "\n=== MATCHING CHECK ===\n";
    $matched = 0;
    $unmatched = [];
    foreach ($lokasiList as $lokasi) {
        if (in_array($lokasi, $seksiList)) {
            $matched++;
        } else {
            $unmatched[] = $lokasi;
        }
    }
    echo "Matched: $matched / " . count($lokasiList) . "\n";
    echo "Unmatched Lokasi (first 10):\n";
    for ($i = 0; $i < min(10, count($unmatched)); $i++) {
        echo "  - '" . $unmatched[$i] . "'\n";
    }
}
