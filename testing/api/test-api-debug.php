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
    
    // Count all by kategori
    $withData = [];
    $noData = 0;
    
    foreach($features as $f) {
        $kat = $f['properties']['kategori'] ?? null;
        if($kat) {
            if(!isset($withData[$kat])) $withData[$kat] = 0;
            $withData[$kat]++;
        } else {
            $noData++;
        }
    }
    
    echo "=== API DATA FOR WILAYAH 16 ===\n";
    echo "Total features: " . count($features) . "\n";
    echo "With kategori: " . (count($features) - $noData) . "\n";
    echo "Without kategori: $noData\n";
    echo "\nKategori breakdown:\n";
    
    $totalTK = 0;
    $totalNeto = 0;
    
    foreach($withData as $kat => $count) {
        echo "  $kat: $count\n";
    }
    
    // Sum TK for features with kategori
    foreach($features as $f) {
        if($f['properties']['kategori'] ?? null) {
            $totalTK += (float)($f['properties']['tk_ha'] ?? 0);
            $totalNeto += (float)($f['properties']['neto'] ?? 0);
        }
    }
    
    echo "\nTotals (only with kategori):\n";
    echo "  Total TK: " . number_format($totalTK, 2) . "\n";
    echo "  Total Neto: " . number_format($totalNeto, 2) . "\n";
    
} else {
    echo "Error: No data returned\n";
}
