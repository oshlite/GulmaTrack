<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MapPublication;

echo "All MapPublications in database:\n";
echo "=".str_repeat("=", 60)."\n";

$all = MapPublication::select('id', 'import_log_id', 'status', 'created_at')
    ->orderBy('id', 'desc')
    ->get();

foreach ($all as $pub) {
    echo sprintf("ID %2d | import_log_id=%2d | status=%10s | %s\n", 
        $pub->id, 
        $pub->import_log_id ?? 'NULL',
        $pub->status,
        $pub->created_at
    );
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "Checking Publication ID 17 directly:\n";
$pub17 = MapPublication::find(17);
if ($pub17) {
    echo "✓ Found: ID={$pub17->id}, import_log_id={$pub17->import_log_id}\n";
} else {
    echo "✗ Not found\n";
}

echo "\nChecking where import_log_id = 16:\n";
$count = MapPublication::where('import_log_id', 16)->count();
echo "Found {$count} records\n";

// Raw database query
echo "\n" . str_repeat("=", 60) . "\n";
echo "RAW DATABASE DATA:\n";
$raw = DB::select('SELECT id, import_log_id, status FROM map_publications ORDER BY id DESC LIMIT 20');
foreach ($raw as $row) {
    echo "ID {$row->id}: import_log_id={$row->import_log_id}, status={$row->status}\n";
}
