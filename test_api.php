<?php

require 'vendor/autoload.php';

// Test API
$testUrls = [
    '/api/wilayah/data',
    '/api/wilayah/geojson/16',
    '/api/wilayah/geojson/17',
];

echo "Testing API endpoints...\n\n";

foreach ($testUrls as $url) {
    $fullUrl = "http://127.0.0.1:8000{$url}";
    echo "Testing: $fullUrl\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fullUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "  ERROR: $error\n";
    } else {
        echo "  HTTP Status: $httpCode\n";
        $data = json_decode($response, true);
        if (is_array($data)) {
            if (isset($data['error'])) {
                echo "  Response Error: " . $data['error'] . "\n";
            } else if (isset($data['features'])) {
                echo "  Features Count: " . count($data['features']) . "\n";
            } else if (isset($data['data'])) {
                echo "  Wilayah Count: " . count($data['data']) . "\n";
            }
        } else {
            echo "  Invalid JSON response\n";
        }
    }
    echo "\n";
}
