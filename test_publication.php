<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MapPublication;

echo "=== TEST MAP PUBLICATION SYSTEM ===\n\n";

// Check current status
$isPublished = MapPublication::isDataPublished();
echo "Status publikasi: " . ($isPublished ? "PUBLISHED" : "NOT PUBLISHED") . "\n\n";

if ($isPublished) {
    $latest = MapPublication::getLatestPublished();
    echo "Terakhir dipublikasi:\n";
    echo "  - Waktu: " . $latest->published_at->format('d M Y H:i:s') . "\n";
    echo "  - Status: " . $latest->status . "\n";
    if ($latest->publisher) {
        echo "  - Oleh: " . $latest->publisher->name . "\n";
    }
}

echo "\n=== SELESAI ===\n";
