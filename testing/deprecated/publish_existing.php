<?php
// Simple script to publish existing data
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get latest import log
$latest = \App\Models\ImportLog::latest('created_at')->first();

if ($latest) {
    // Check if already published
    $exists = \App\Models\MapPublication::where('import_log_id', $latest->id)->first();
    
    if ($exists) {
        echo "✓ Already published! Import Log ID: {$latest->id}\n";
        echo "Published at: {$exists->published_at}\n";
    } else {
        \App\Models\MapPublication::create([
            'import_log_id' => $latest->id,
            'status' => 'published',
            'published_at' => now(),
            'published_by' => 1,
            'notes' => 'Auto-published'
        ]);
        echo "✓ Published! Import Log ID: {$latest->id}\n";
        echo "Date: {$latest->created_at}\n";
    }
} else {
    echo "✗ No import logs found\n";
}
?>
