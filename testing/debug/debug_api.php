<?php
// Check API response

$url = 'http://localhost/api/wilayah/records/16';
$response = file_get_contents($url);
$data = json_decode($response, true);

echo "=== API RESPONSE CHECK ===\n";
echo "Total features: " . count($data['features']) . "\n\n";

if (count($data['features']) > 0) {
    $feature = $data['features'][0];
    echo "First feature properties:\n";
    echo json_encode($feature['properties'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}
