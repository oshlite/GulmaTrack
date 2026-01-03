<?php

$ch = curl_init('http://localhost:8000/api/wilayah/records/16');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
echo "=== API /wilayah/records/16 ===\n";
echo "Total records: " . ($data['total'] ?? 'ERROR') . "\n";
echo "Features in response: " . (count($data['features'] ?? []) ? count($data['features']) : 'ERROR') . "\n";

$bersih = 0;
foreach($data['features'] ?? [] as $f) {
    if (strpos(strtolower($f['properties']['kategori'] ?? ''), 'bersih') !== false) {
        $bersih++;
    }
}
echo "Bersih in records: $bersih\n";
