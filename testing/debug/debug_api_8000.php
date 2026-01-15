<?php
// Check API response on port 8000

$url = 'http://localhost:8000/api/wilayah/records/16';
echo "Fetching: $url\n";

$response = @file_get_contents($url);
if ($response === false) {
    echo "Failed to fetch URL\n";
    exit(1);
}

$data = json_decode($response, true);

echo "=== API RESPONSE CHECK ===\n";
echo "Total features: " . count($data['features']) . "\n\n";

if (count($data['features']) > 0) {
    $feature = $data['features'][0];
    echo "First feature properties:\n";
    echo json_encode($feature['properties'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}
