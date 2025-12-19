<?php

require __DIR__ . '/vendor/autoload.php';

use App\Utils\CoordinateTransformer;

// Test dengan koordinat dari Wil16.geojson
$easting = 524308.254454578389414;
$northing = 9460823.61877775192261; // Contoh, perlu dicek dari data asli

echo "Testing UTM to WGS84 Conversion:\n";
echo "================================\n\n";

echo "Input (UTM Zone 48S - EPSG:32748):\n";
echo "Easting:  " . number_format($easting, 2) . "\n";
echo "Northing: " . number_format($northing, 2) . "\n\n";

$result = CoordinateTransformer::utm32748ToWgs84($easting, $northing);

echo "Output (WGS84 - EPSG:4326):\n";
echo "Latitude:  " . $result['lat'] . "\n";
echo "Longitude: " . $result['lng'] . "\n\n";

echo "Expected for Lampung Tengah:\n";
echo "Latitude:  -4.8 to -5.0\n";
echo "Longitude: 104.5 to 105.5\n\n";

// Test dengan file GeoJSON asli
echo "\n\nTesting with actual GeoJSON file:\n";
echo "==================================\n";

$geojsonPath = __DIR__ . '/data/Wil16.geojson';
if (file_exists($geojsonPath)) {
    $geojson = json_decode(file_get_contents($geojsonPath), true);
    
    echo "Original CRS: " . ($geojson['crs']['properties']['name'] ?? 'Not specified') . "\n";
    
    // Get first coordinate
    $coords = $geojson['features'][0]['geometry']['coordinates'][0][0][0];
    echo "First coordinate (UTM): [" . $coords[0] . ", " . $coords[1] . "]\n";
    
    $converted = CoordinateTransformer::utm32748ToWgs84($coords[0], $coords[1]);
    echo "Converted (WGS84): [" . $converted['lng'] . ", " . $converted['lat'] . "]\n";
    
    // Convert entire GeoJSON
    echo "\nConverting entire GeoJSON...\n";
    $convertedGeoJson = CoordinateTransformer::convertGeoJsonToWgs84($geojson);
    
    $newCoords = $convertedGeoJson['features'][0]['geometry']['coordinates'][0][0][0];
    echo "After conversion: [" . $newCoords[0] . ", " . $newCoords[1] . "]\n";
    echo "New CRS: " . $convertedGeoJson['crs']['properties']['name'] . "\n";
}
