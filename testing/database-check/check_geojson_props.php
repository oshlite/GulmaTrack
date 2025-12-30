<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$wilayah_number = 16;
$filePath = storage_path("../data/Wil{$wilayah_number}.geojson");

$geojson = json_decode(file_get_contents($filePath), true);

echo "=== Properties in GeoJSON Features (Wilayah $wilayah_number) ===\n\n";

if (isset($geojson['features']) && !empty($geojson['features'])) {
    $firstFeature = $geojson['features'][0];
    
    if (isset($firstFeature['properties'])) {
        echo "Properties available:\n";
        foreach ($firstFeature['properties'] as $key => $value) {
            echo "  - $key: $value\n";
        }
        
        echo "\n\n=== Sample from first 3 features ===\n";
        foreach (array_slice($geojson['features'], 0, 3) as $idx => $feature) {
            echo "\nFeature " . ($idx + 1) . ":\n";
            if (isset($feature['properties'])) {
                foreach ($feature['properties'] as $key => $value) {
                    if (strlen($value) < 50) {
                        echo "  $key: $value\n";
                    }
                }
            }
        }
    }
} else {
    echo "No features found\n";
}
