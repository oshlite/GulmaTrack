<?php

require 'vendor/autoload.php';

use App\Utils\CoordinateTransformer;

// Test dengan file Wil16
$filePath = __DIR__ . '/data/Wil16.geojson';

if (!file_exists($filePath)) {
    echo "File not found: $filePath\n";
    exit(1);
}

$geojson = json_decode(file_get_contents($filePath), true);

echo "Original GeoJSON:\n";
echo "- Type: " . $geojson['type'] . "\n";
echo "- CRS: " . json_encode($geojson['crs']) . "\n";
echo "- Features count: " . count($geojson['features']) . "\n";
echo "\n";

// Get first feature
$firstFeature = $geojson['features'][0];
echo "First feature (before conversion):\n";
echo "- Properties: " . json_encode($firstFeature['properties']) . "\n";
if (isset($firstFeature['geometry']['coordinates'][0][0])) {
    $coords = $firstFeature['geometry']['coordinates'][0][0][0];
    echo "- First coordinate: " . json_encode($coords) . "\n";
}
echo "\n";

// Convert
$converted = CoordinateTransformer::convertGeoJsonToWgs84($geojson);

echo "Converted GeoJSON:\n";
echo "- CRS: " . json_encode($converted['crs']) . "\n";
echo "- Features count: " . count($converted['features']) . "\n";
echo "\n";

// Get first feature after conversion
$firstFeatureConverted = $converted['features'][0];
echo "First feature (after conversion):\n";
echo "- Type: " . $firstFeatureConverted['geometry']['type'] . "\n";
if (isset($firstFeatureConverted['geometry']['coordinates'][0][0][0])) {
    $coords = $firstFeatureConverted['geometry']['coordinates'][0][0][0];
    echo "- First coordinate: " . json_encode($coords) . "\n";
    echo "  (should be [lng, lat] in WGS84 format)\n";
}
echo "\n";

// Return as JSON for API test
echo "API Response (JSON):\n";
echo json_encode($converted, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
