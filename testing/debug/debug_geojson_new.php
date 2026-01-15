<?php
// Check GeoJSON API response

$url = 'http://localhost:8000/api/wilayah/geojson/16';
echo "Fetching: $url\n";

$response = @file_get_contents($url);
if ($response === false) {
    echo "Failed to fetch URL\n";
    exit(1);
}

$data = json_decode($response, true);

echo "=== GeoJSON API RESPONSE CHECK ===\n";
echo "Total features: " . count($data['features']) . "\n\n";

if (count($data['features']) > 0) {
    for ($i = 0; $i < min(3, count($data['features'])); $i++) {
        $feature = $data['features'][$i];
        echo "Feature $i:\n";
        echo "  hasil: " . ($feature['properties']['hasil'] ?? 'MISSING') . "\n";
        echo "  umur: " . ($feature['properties']['umur'] ?? 'MISSING') . "\n";
        echo "  tnm_sts: " . ($feature['properties']['tnm_sts'] ?? 'MISSING') . "\n";
        echo "  activitas: " . ($feature['properties']['activitas'] ?? 'MISSING') . "\n";
        echo "  tanggal: " . ($feature['properties']['tanggal'] ?? 'MISSING') . "\n";
        echo "  kategori: " . ($feature['properties']['kategori'] ?? 'MISSING') . "\n";
        echo "\n";
    }
}
