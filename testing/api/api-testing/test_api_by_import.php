<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing /api/data-gulma/by-import/12 ===\n\n";

// Create a test request
$request = \Illuminate\Http\Request::create('/api/data-gulma/by-import/12', 'GET');

// Get the controller
$controller = new \App\Http\Controllers\AdminController();

// Call the method
$response = $controller->getDataByImport(12);
$data = json_decode($response->getContent(), true);

echo "Response:\n";
echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

echo "\n\nData count: " . (isset($data['data']) ? count($data['data']) : 'N/A') . "\n";

if (isset($data['data']) && count($data['data']) > 0) {
    echo "Sample records:\n";
    for ($i = 0; $i < min(3, count($data['data'])); $i++) {
        $r = $data['data'][$i];
        echo "  [{$i}] wilayah_id={$r['wilayah_id']}, seksi={$r['seksi']}, kategori={$r['kategori']}\n";
    }
}
