<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING GUEST API ACCESS ===\n\n";

// Simulate API request without admin parameter
$request = Request::create('/api/wilayah/geojson/16', 'GET');
$controller = new \App\Http\Controllers\WilayahController();

try {
    $response = $controller->getGeojson(16, $request);
    $data = json_decode($response->getContent(), true);
    
    echo "Response status: " . $response->getStatusCode() . "\n";
    echo "Total features: " . count($data['features'] ?? []) . "\n";
    
    $withKategori = array_filter($data['features'] ?? [], function($f) {
        return isset($f['properties']['kategori']);
    });
    
    echo "Features with kategori: " . count($withKategori) . "\n\n";
    
    if (count($withKategori) > 0) {
        echo "Sample features:\n";
        foreach (array_slice($withKategori, 0, 3) as $f) {
            echo "  - Seksi: {$f['properties']['seksi']} | Kategori: {$f['properties']['kategori']}\n";
        }
    } else {
        echo "⚠️ No features with kategori found!\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
