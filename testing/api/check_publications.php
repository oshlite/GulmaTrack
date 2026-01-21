<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

$app = app();
$db = $app->make('db');

// Check all publications
$pubs = $db->table('map_publications')
    ->where('status', 'published')
    ->orderBy('published_at', 'desc')
    ->get();

echo "=== SEMUA PUBLIKASI DI DATABASE ===\n";
echo "Total: " . count($pubs) . "\n\n";

foreach ($pubs as $pub) {
    echo "ID: {$pub->id} | {$pub->tahun}/{$pub->bulan}/W{$pub->minggu} | Import: {$pub->import_log_id} | Published: {$pub->published_at}\n";
}

echo "\n=== CHECK IMPORT LOGS ===\n";
$imports = $db->table('import_logs')
    ->where('status', 'success')
    ->orderBy('tahun', 'desc')
    ->orderBy('bulan', 'desc')
    ->orderBy('minggu', 'desc')
    ->get();

echo "Total Import Logs: " . count($imports) . "\n\n";
foreach ($imports as $imp) {
    echo "ID: {$imp->id} | {$imp->tahun}/{$imp->bulan}/W{$imp->minggu} | File: {$imp->nama_file}\n";
}
