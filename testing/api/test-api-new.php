<?php

$url = 'http://localhost:8000/api/wilayah/geojson/16';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if ($data) {
    $features = $data['features'] ?? [];
    $withKategori = array_filter($features, fn($f) => isset($f['properties']['kategori']));
    
    $kategoris = array_map(fn($f) => $f['properties']['kategori'], $withKategori);
    $kategorisCount = array_count_values($kategoris);
    
    $totalTK = 0;
    foreach($withKategori as $f) {
        $totalTK += (float)($f['properties']['tk_ha'] ?? 0);
    }
    
    echo "=== API RESPONSE FOR WILAYAH 16 ===\n";
    echo "Total features: " . count($features) . "\n";
    echo "Features dengan kategori: " . count($withKategori) . "\n";
    echo "\nKategori count:\n";
    foreach($kategorisCount as $kat => $count) {
        echo "  $kat: $count\n";
    }
    echo "\nTotal TK/HA: " . number_format($totalTK, 2) . "\n";
} else {
    echo "Error: No data returned\n";
}
