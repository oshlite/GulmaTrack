<?php
require 'vendor/autoload.php';

echo "=== Testing API via HTTP Request ===\n\n";

// Use curl to make actual HTTP request
$url = "http://localhost/api/wilayah/geojson/16?admin=1&import_id=12";
echo "Testing URL: $url\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'X-Admin-Request: 1'
]);

$response = curl_exec($ch);
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status: $statusCode\n\n";

$data = json_decode($response, true);

echo "Response type: " . ($data['type'] ?? 'N/A') . "\n";
echo "Features count: " . count($data['features'] ?? []) . "\n\n";

if (count($data['features'] ?? []) > 0) {
    echo "✓ API is returning " . count($data['features']) . " features!\n";
    
    echo "\nFirst 3 features:\n";
    for ($i = 0; $i < min(3, count($data['features'])); $i++) {
        $f = $data['features'][$i];
        $props = $f['properties'];
        echo "  [$i] Lokasi={$props['Lokasi']}, kategori={$props['kategori']}\n";
    }
} else {
    echo "❌ NO FEATURES!\n";
}
