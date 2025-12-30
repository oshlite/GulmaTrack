<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== MAP_PUBLICATIONS TABLE STRUCTURE ===\n\n";

$columns = DB::select("
    SELECT column_name, data_type, is_nullable 
    FROM information_schema.columns 
    WHERE table_name = 'map_publications' 
    ORDER BY ordinal_position
");

foreach ($columns as $col) {
    echo sprintf("%-20s %-15s %s\n", 
        $col->column_name, 
        $col->data_type,
        $col->is_nullable === 'YES' ? 'NULL' : 'NOT NULL'
    );
}

echo "\n=== MAP_PUBLICATIONS DATA ===\n\n";

$pubs = DB::table('map_publications')
    ->orderBy('id', 'desc')
    ->limit(5)
    ->get();

foreach ($pubs as $p) {
    echo "ID: $p->id | Status: $p->status | Published at: " . ($p->published_at ?? 'NULL') . "\n";
}
