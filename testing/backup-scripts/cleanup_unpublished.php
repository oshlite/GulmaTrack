<?php
/**
 * Cleanup script to delete unpublished import data
 * Only keep data for published imports
 */

require __DIR__ . '/bootstrap/app.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DataGulma;
use App\Models\MapPublication;

echo "====== CLEANUP UNPUBLISHED IMPORTS ======\n\n";

// Get all published import_log_ids
$published = MapPublication::where('status', 'published')->pluck('import_log_id')->toArray();
echo "Published import_log_ids: " . implode(', ', $published) . "\n";

echo "\n--- BEFORE ---\n";
echo "Total DataGulma records: " . DataGulma::count() . "\n";

// Show breakdown BEFORE
$countsBefore = DataGulma::groupBy('import_log_id')
    ->selectRaw('import_log_id, count(*) as total')
    ->orderBy('import_log_id', 'desc')
    ->get();

foreach ($countsBefore as $item) {
    $pub = MapPublication::where('import_log_id', $item->import_log_id)->first();
    $status = $pub ? '✓ PUBLISHED' : '✗ UNPUBLISHED';
    echo "  Import {$item->import_log_id}: {$item->total} records [{$status}]\n";
}

// Delete DataGulma records that are NOT in published imports
echo "\n--- DELETING UNPUBLISHED DATA ---\n";
$deleted = DataGulma::whereNotIn('import_log_id', $published)->delete();
echo "Deleted $deleted unpublished DataGulma records\n";

echo "\n--- AFTER ---\n";
echo "Total DataGulma records: " . DataGulma::count() . "\n";

// Show breakdown AFTER
$countsAfter = DataGulma::groupBy('import_log_id')
    ->selectRaw('import_log_id, count(*) as total')
    ->orderBy('import_log_id', 'desc')
    ->get();

foreach ($countsAfter as $item) {
    $pub = MapPublication::where('import_log_id', $item->import_log_id)->first();
    $status = $pub ? '✓ PUBLISHED' : '✗ UNPUBLISHED';
    $period = $pub ? "{$pub->tahun}/{$pub->bulan}/W{$pub->minggu}" : 'UNKNOWN';
    echo "  Import {$item->import_log_id} ({$period}): {$item->total} records [{$status}]\n";
}

echo "\n✅ Cleanup complete!\n";
