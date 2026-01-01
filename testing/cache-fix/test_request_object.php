<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simulate HTTP request with query parameters
$queryString = 'admin=1&import_id=7';
$_GET['admin'] = '1';
$_GET['import_id'] = '7';

// Test query parameter extraction
$request = \Illuminate\Http\Request::create('/?admin=1&import_id=7', 'GET');

echo "Test Request Object:\n";
echo "============================\n";
echo "Query string: " . $request->getQueryString() . "\n";
echo "Admin param: " . $request->query('admin') . "\n";
echo "Import ID param: " . $request->query('import_id') . "\n";
echo "Import ID is: " . ($request->query('import_id') ? "TRUTHY" : "FALSY") . "\n";

// Test type
$importId = $request->query('import_id');
echo "Type of import_id: " . gettype($importId) . "\n";
echo "Value of import_id: " . var_export($importId, true) . "\n";

// Test in conditional
if ($importId) {
    echo "Import ID passed IF test: YES\n";
} else {
    echo "Import ID passed IF test: NO\n";
}
?>
