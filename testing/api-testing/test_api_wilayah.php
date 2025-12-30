<?php
// Quick test untuk /api/wilayah/data endpoint
// Run dengan: php test_api_wilayah.php

define('LARAVEL_START', microtime(true));

// Bootstrap Laravel
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::create('/api/wilayah/data', 'GET')
);

echo "=== API Response ===\n";
echo "Status: " . $response->getStatusCode() . "\n";
echo "Content-Type: " . $response->headers->get('content-type') . "\n";
echo "\n=== Response Body ===\n";
echo $response->getContent() . "\n";

// Parse and check JSON
try {
    $data = json_decode($response->getContent(), true);
    echo "\n=== JSON Decoded ===\n";
    echo "Has 'data' key: " . (isset($data['data']) ? 'YES' : 'NO') . "\n";
    if (isset($data['data'])) {
        echo "data is array: " . (is_array($data['data']) ? 'YES' : 'NO') . "\n";
        echo "data count: " . (is_array($data['data']) ? count($data['data']) : 'N/A') . "\n";
        if (is_array($data['data']) && count($data['data']) > 0) {
            echo "First item: " . json_encode($data['data'][0]) . "\n";
        }
    }
    if (isset($data['error'])) {
        echo "Error: " . $data['error'] . "\n";
    }
} catch (\Exception $e) {
    echo "Failed to parse JSON: " . $e->getMessage() . "\n";
}
?>
