<?php
// Test API wilayah geojson dengan data merged
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

try {
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Get sample data from wilayah 16
    $controller = new \App\Http\Controllers\WilayahController();
    
    // Simulate admin request
    $request = new \Illuminate\Http\Request();
    $request->headers->set('X-Admin-Request', '1');
    
    $response = $controller->getGeojson(16, $request);
    $data = json_decode($response->getContent(), true);
    
    echo "=== Testing Wilayah 16 GeoJSON API ===\n";
    echo "Total Features: " . count($data['features'] ?? []) . "\n";
    
    // Count features with kategori data
    $withKategori = 0;
    $sampleFeature = null;
    
    foreach ($data['features'] ?? [] as $feature) {
        if (isset($feature['properties']['kategori']) && $feature['properties']['kategori']) {
            $withKategori++;
            if (!$sampleFeature) {
                $sampleFeature = $feature;
            }
        }
    }
    
    echo "Features with Kategori: " . $withKategori . "\n";
    
    if ($sampleFeature) {
        echo "\n=== Sample Feature with Data ===\n";
        $props = $sampleFeature['properties'];
        echo "Seksi: " . ($props['seksi'] ?? $props['Seksi'] ?? $props['SEKSI'] ?? 'N/A') . "\n";
        echo "Kategori: " . ($props['kategori'] ?? 'N/A') . "\n";
        echo "PG: " . ($props['pg'] ?? 'N/A') . "\n";
        echo "FM: " . ($props['fm'] ?? 'N/A') . "\n";
        echo "Aktivitas: " . ($props['activitas'] ?? 'N/A') . "\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
?>
