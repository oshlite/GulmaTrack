<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing API Merge ===\n\n";

// Simulate API call
$wilayah_number = 16;
$filePath = storage_path("../data/Wil{$wilayah_number}.geojson");

if (!file_exists($filePath)) {
    echo "GeoJSON file not found!\n";
    exit;
}

$geojson = json_decode(file_get_contents($filePath), true);

// Get status_gulma data from database
$gulmaData = DB::table('data_gulma')
    ->where('wilayah_id', $wilayah_number)
    ->get();

echo "Found " . $gulmaData->count() . " records in database for wilayah $wilayah_number\n\n";

// Create lookup map
$gulmaMap = [];
foreach ($gulmaData as $data) {
    $gulmaMap[$data->id_feature] = [
        'status_gulma' => $data->status_gulma,
        'persentase' => $data->persentase,
        'tanggal' => $data->tanggal
    ];
}

// Check first 5 features
$matched = 0;
$notMatched = 0;

if (isset($geojson['features'])) {
    foreach (array_slice($geojson['features'], 0, 10) as $feature) {
        if (isset($feature['properties'])) {
            $props = $feature['properties'];
            $idFeature = $props['Lokasi'] ?? $props['SEKSI'] ?? $props['Seksi'] ?? $props['seksi'] ?? $props['id_feature'] ?? null;
            
            if ($idFeature && isset($gulmaMap[$idFeature])) {
                echo "✓ Matched: Lokasi=$idFeature -> Status={$gulmaMap[$idFeature]['status_gulma']}\n";
                $matched++;
            } else {
                echo "✗ Not matched: Lokasi=" . ($idFeature ?? 'NULL') . "\n";
                $notMatched++;
            }
        }
    }
    
    echo "\n=== Summary ===\n";
    echo "Matched: $matched\n";
    echo "Not matched: $notMatched\n";
    echo "Total features in GeoJSON: " . count($geojson['features']) . "\n";
    echo "Total data in database: " . $gulmaData->count() . "\n";
}
