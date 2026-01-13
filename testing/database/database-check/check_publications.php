<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== MAP_PUBLICATIONS TABLE ===\n\n";

$pubs = DB::table('map_publications')
    ->orderBy('id', 'desc')
    ->limit(10)
    ->get(['id', 'status', 'import_log_id', 'published_at', 'notes']);

foreach ($pubs as $p) {
    echo sprintf(
        "ID: %d | Status: %s | Import: %s | Published: %s\n",
        $p->id,
        $p->status,
        $p->import_log_id ?: 'NULL',
        $p->published_at ?: 'NULL'
    );
}
