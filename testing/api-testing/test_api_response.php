<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing API Response for Wilayah 16 Import 12 ===\n\n";

// Create a test request
$request = \Illuminate\Http\Request::create('/api/wilayah/geojson/16?import_id=12&admin=1', 'GET');
$request->setRouteResolver(function () {
    return app('router')->getRoutes()->match($request);
});

// Get the controller
$controller = new \App\Http\Controllers\WilayahController();

// Call the method
$response = $controller->getGeojson(16, $request);
$data = json_decode($response->getContent(), true);

echo "Response type: " . gettype($data) . "\n";
echo "Features count: " . (isset($data['features']) ? count($data['features']) : 'N/A') . "\n";

if (isset($data['features']) && count($data['features']) > 0) {
    echo "Sample features:\n";
    for ($i = 0; $i < min(3, count($data['features'])); $i++) {
        $f = $data['features'][$i];
        echo "  Feature {$i}: ";
        if (isset($f['properties']['Lokasi'])) echo "Lokasi=" . $f['properties']['Lokasi'] . " ";
        if (isset($f['properties']['SEKSI'])) echo "SEKSI=" . $f['properties']['SEKSI'] . " ";
        if (isset($f['properties']['kategori'])) echo "kategori=" . $f['properties']['kategori'] . " ";
        echo "\n";
    }
} else {
    echo "NO FEATURES!\n";
    if (isset($data['error'])) {
        echo "Error: " . $data['error'] . "\n";
    }
}
