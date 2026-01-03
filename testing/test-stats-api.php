<?php

$ch = curl_init('http://localhost:8000/api/wilayah/stats/16');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
echo "=== API /wilayah/stats/16 ===\n";
echo "Bersih: " . ($data['bersih_count'] ?? 'ERROR') . "\n";
echo "Ringan: " . ($data['ringan_count'] ?? 'ERROR') . "\n";
echo "Total TK: " . ($data['total_tk'] ?? 'ERROR') . "\n";
echo "Total Neto: " . ($data['total_neto'] ?? 'ERROR') . "\n";
