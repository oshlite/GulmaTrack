<?php
// Test API dengan header admin
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== Testing API Request ===\n";

// Simulate admin request
$request = \Illuminate\Http\Request::create('/api/wilayah/geojson/16?admin=1', 'GET');
$request->headers->set('X-Admin-Request', '1');

$controller = new \App\Http\Controllers\WilayahController();
$response = $controller->getGeojson(16, $request);

$data = json_decode($response->getContent(), true);

echo "Status Code: " . $response->getStatusCode() . "\n";
echo "Features Count: " . count($data['features'] ?? []) . "\n";

if (isset($data['features']) && count($data['features']) > 0) {
    $withKategori = 0;
    foreach ($data['features'] as $feature) {
        if (isset($feature['properties']['kategori']) && $feature['properties']['kategori']) {
            $withKategori++;
        }
    }
    echo "Features with Kategori: $withKategori\n";
    
    if ($withKategori > 0) {
        echo "\n✅ SUCCESS! Data ter-merge dengan benar!\n";
        
        // Show sample
        foreach ($data['features'] as $feature) {
            if (isset($feature['properties']['kategori'])) {
                $props = $feature['properties'];
                echo "\nSample Feature:\n";
                echo "  Seksi: " . ($props['seksi'] ?? 'N/A') . "\n";
                echo "  Kategori: " . ($props['kategori'] ?? 'N/A') . "\n";
                echo "  PG: " . ($props['pg'] ?? 'N/A') . "\n";
                break;
            }
        }
    } else {
        echo "\n❌ FAIL! Features ada tapi tidak ada kategori (data tidak ter-merge)\n";
    }
} else {
    echo "\n❌ FAIL! Tidak ada features\n";
    if (isset($data['error'])) {
        echo "Error: " . $data['error'] . "\n";
    }
}
?>
