<?php
// Simple CLI test untuk verify API endpoints working

require 'vendor/autoload.php';

use App\Http\Controllers\WilayahController;

// Create controller instance
$controller = new WilayahController();

echo "==== TEST GEOJSON API ====\n\n";

// Test getGeojson method
echo "1. Testing getGeojson(16)...\n";
$response = $controller->getGeojson(16);
$data = json_decode($response->getContent(), true);
echo "   Status: " . ($response->status() === 200 ? "✓ OK" : "✗ ERROR: " . $response->status()) . "\n";
echo "   Features count: " . (isset($data['features']) ? count($data['features']) : "ERROR") . "\n";
if (isset($data['features'][0])) {
    $first = $data['features'][0];
    echo "   First feature geometry type: " . $first['geometry']['type'] . "\n";
    if (isset($first['geometry']['coordinates'][0][0][0])) {
        $coords = $first['geometry']['coordinates'][0][0][0];
        echo "   First coordinate: [" . $coords[0] . ", " . $coords[1] . "]\n";
        echo "   Coordinate range: lng is " . (is_numeric($coords[0]) && $coords[0] > 100 && $coords[0] < 120 ? "✓ valid" : "✗ invalid") . "\n";
        echo "   Coordinate range: lat is " . (is_numeric($coords[1]) && $coords[1] > -10 && $coords[1] < 0 ? "✓ valid" : "✗ invalid") . "\n";
    }
}
echo "\n";

echo "2. Testing getData()...\n";
$response = $controller->getData();
$data = json_decode($response->getContent(), true);
echo "   Status: " . ($response->status() === 200 ? "✓ OK" : "✗ ERROR: " . $response->status()) . "\n";
echo "   Wilayah count: " . (isset($data['data']) ? count($data['data']) : "ERROR") . "\n";
if (isset($data['data'])) {
    foreach ($data['data'] as $w) {
        echo "   - Wilayah {$w['wilayah']}: {$w['feature_count']} plots, {$w['total_area']} Ha\n";
    }
}
echo "\n";

echo "==== ALL TESTS COMPLETED ====\n";
