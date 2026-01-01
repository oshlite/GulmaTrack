<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MapPublication;
use App\Models\DataGulma;

echo "🔍 Debugging getDataByImport logic for Import 16:\n";
echo "=".str_repeat("=", 60)."\n";

// Check if import 16 was published
$pub16 = MapPublication::where('import_log_id', 16)->first();
echo "✓ MapPublication for import_log_id=16: " . ($pub16 ? "FOUND (ID={$pub16->id})" : "NOT FOUND") . "\n";

// Get latest publication
$latest = MapPublication::orderBy('created_at', 'desc')->first();
echo "✓ Latest MapPublication: ID={$latest->id}, import_log_id={$latest->import_log_id}\n";

// Check data for latest import
$data17 = DataGulma::where('import_log_id', $latest->import_log_id)->count();
echo "✓ DataGulma records for import_log_id={$latest->import_log_id}: {$data17}\n";

// Simulate the getDataByImport logic
echo "\n📊 Simulating getDataByImport(16):\n";
echo "=".str_repeat("=", 60)."\n";

$importId = 16;
$data = DataGulma::where('import_log_id', $importId)->get();
echo "Step 1: Query import 16 → " . $data->count() . " records\n";

if ($data->count() === 0) {
    echo "Step 2: No data found, checking publication...\n";
    
    $publication = MapPublication::where('import_log_id', $importId)
        ->orderBy('created_at', 'desc')
        ->first();
    
    echo "Step 3: Publication check → " . ($publication ? "FOUND" : "NOT FOUND") . "\n";
    
    if ($publication) {
        echo "Step 4: Found publication, getting latest...\n";
        
        $latestPublication = MapPublication::orderBy('created_at', 'desc')->first();
        echo "Step 5: Latest publication import_id = {$latestPublication->import_log_id}\n";
        
        if ($latestPublication && $latestPublication->import_log_id) {
            $data = DataGulma::where('import_log_id', $latestPublication->import_log_id)->get();
            echo "Step 6: Retrieved {$data->count()} records from import {$latestPublication->import_log_id}\n";
        }
    } else {
        echo "Step 4: Import 16 was NOT published, no fallback\n";
    }
}

echo "\n✅ Final result: {$data->count()} records would be returned\n";
