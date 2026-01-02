<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

echo "=== Testing API Response for Import 12, Wilayah 16 ===\n\n";

// Create request and simulate it through the kernel
$request = \Illuminate\Http\Request::create('/api/wilayah/geojson/16?admin=1&import_id=12', 'GET');
$request->headers->set('X-Admin-Request', '1');
$app->instance('request', $request);

$response = $kernel->handle($request);

$content = $response->getContent();
$data = json_decode($content, true);

echo "Response status: " . $response->status() . "\n";
echo "Response type: " . ($data['type'] ?? 'N/A') . "\n";
echo "Features count: " . count($data['features'] ?? []) . "\n";
echo "Keys in response: " . implode(', ', array_keys($data)) . "\n\n";

if (count($data['features'] ?? []) > 0) {
    echo "First 3 features:\n";
    for ($i = 0; $i < min(3, count($data['features'])); $i++) {
        $f = $data['features'][$i];
        $props = $f['properties'];
        echo "  Feature $i:\n";
        echo "    Lokasi: " . ($props['Lokasi'] ?? 'N/A') . "\n";
        echo "    seksi: " . ($props['seksi'] ?? 'N/A') . "\n";
        echo "    kategori: " . ($props['kategori'] ?? 'N/A') . "\n";
        echo "    pg: " . ($props['pg'] ?? 'N/A') . "\n";
        echo "\n";
    }
} else {
    echo "❌ NO FEATURES IN RESPONSE!\n";
    echo "\nFull response:\n";
    echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
}
