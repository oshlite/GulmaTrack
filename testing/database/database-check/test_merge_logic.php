<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing Data Merge Logic ===\n\n";

// Simulate what the controller does
$wilayahNum = 16;
$importId = 12;

// Load GeoJSON
$filePath = base_path("datala/Wil{$wilayahNum}.geojson");
$geojson = json_decode(file_get_contents($filePath), true);

echo "GeoJSON loaded: " . count($geojson['features']) . " features\n\n";

// Get database data
$gulmaData = \App\Models\DataGulma::where('wilayah_id', $wilayahNum)
    ->where('import_log_id', $importId)
    ->get();

echo "Database records: " . count($gulmaData) . "\n\n";

// Create lookup map (like the controller does)
$gulmaMap = [];
foreach ($gulmaData as $data) {
    $normalizedSeksi = strtolower(trim($data->seksi));
    $gulmaMap[$normalizedSeksi] = $data;
    echo "Added to map: '{$normalizedSeksi}' => {$data->seksi} (kategori: {$data->kategori})\n";
}

echo "\n✓ Gulma map size: " . count($gulmaMap) . "\n\n";

// Check GeoJSON properties
echo "Sample GeoJSON features:\n";
for ($i = 0; $i < min(5, count($geojson['features'])); $i++) {
    $f = $geojson['features'][$i];
    $lokasi = $f['properties']['Lokasi'] ?? 'N/A';
    $normalized = strtolower(trim($lokasi));
    $inMap = isset($gulmaMap[$normalized]) ? '✓ MATCH' : '✗ NO MATCH';
    echo "  [$i] Lokasi='$lokasi' => normalized='$normalized' => $inMap\n";
}

echo "\n\n=== Testing Merge ===\n";

$mergedCount = 0;
foreach ($geojson['features'] as &$feature) {
    if (isset($feature['properties'])) {
        $seksiValue = $feature['properties']['Lokasi'] ?? null;
        $normalizedSeksiValue = $seksiValue ? strtolower(trim($seksiValue)) : null;
        
        if ($normalizedSeksiValue && isset($gulmaMap[$normalizedSeksiValue])) {
            $data = $gulmaMap[$normalizedSeksiValue];
            
            // Inject data
            $feature['properties']['seksi'] = $data->seksi;
            $feature['properties']['kategori'] = $data->kategori;
            $feature['properties']['pg'] = $data->pg;
            
            echo "✓ Merged feature: Lokasi=$seksiValue, kategori={$data->kategori}\n";
            $mergedCount++;
        } else {
            $feature['properties']['kategori'] = '';
            echo "✗ No match for: Lokasi=$seksiValue\n";
        }
    }
}

echo "\n✓ Merged $mergedCount features\n";

// Now check filter condition
echo "\n\n=== Testing Filter (import_id=$importId) ===\n";

$beforeFilter = count($geojson['features']);
echo "Before filter: $beforeFilter features\n";

$geojson['features'] = array_filter($geojson['features'], function($feature) {
    return isset($feature['properties']['kategori']) && 
           !empty(trim($feature['properties']['kategori']));
});

$geojson['features'] = array_values($geojson['features']);
$afterFilter = count($geojson['features']);

echo "After filter: $afterFilter features\n";
echo "Removed: " . ($beforeFilter - $afterFilter) . " features\n";
