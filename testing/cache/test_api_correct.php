<?php
// Test if the API endpoint receives the import_id parameter correctly

$wilayahNum = 16;
$importId = 7;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Create request with proper query parameters using create() method
$request = \Illuminate\Http\Request::create("/api/wilayah/geojson/{$wilayahNum}?admin=1&import_id={$importId}", 'GET');

// Manually call getGeojson with simulated request
$controller = new \App\Http\Controllers\WilayahController();
$response = $controller->getGeojson($wilayahNum, $request);

// Get the response data
$data = json_decode($response->getContent(), true);

echo "Test Results:\n";
echo "=============\n";
echo "Wilayah: {$wilayahNum}\n";
echo "Import ID: {$importId}\n";
echo "Features received: " . count($data['features']) . "\n";

// Check kategori distribution
$kategoriDist = [];
foreach ($data['features'] as $feature) {
    if (isset($feature['properties']['kategori'])) {
        $kategori = $feature['properties']['kategori'];
        if (!isset($kategoriDist[$kategori])) {
            $kategoriDist[$kategori] = 0;
        }
        $kategoriDist[$kategori]++;
    }
}

echo "\nKategori distribution:\n";
foreach ($kategoriDist as $k => $c) {
    echo "  $k: $c\n";
}

// Show first 3 features
echo "\nFirst 3 features:\n";
for ($i = 0; $i < min(3, count($data['features'])); $i++) {
    $f = $data['features'][$i];
    echo "  Feature $i: " . $f['properties']['seksi'] . " (kategori: " . $f['properties']['kategori'] . ")\n";
}
?>
