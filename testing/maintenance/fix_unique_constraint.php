<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== FIXING DATA_GULMA UNIQUE CONSTRAINT ===\n\n";

// Drop the unique constraint on id_feature
// First check if it exists
$constraints = DB::select("SELECT constraint_name FROM information_schema.table_constraints WHERE table_name='data_gulma' AND constraint_type='UNIQUE'");

echo "Unique constraints on data_gulma:\n";
foreach ($constraints as $c) {
    echo "- " . $c->constraint_name . "\n";
}

echo "\nDropping unique constraint on id_feature...\n";
try {
    DB::statement("ALTER TABLE data_gulma DROP CONSTRAINT IF EXISTS data_gulma_id_feature_unique");
    echo "✓ Constraint dropped\n";
} catch (\Exception $e) {
    echo "Note: " . $e->getMessage() . "\n";
}

// Make sure there's a composite unique constraint (wilayah_id + id_feature) instead
echo "\nAdding composite unique constraint (wilayah_id + id_feature)...\n";
try {
    DB::statement("ALTER TABLE data_gulma ADD CONSTRAINT data_gulma_wilayah_id_feature_unique UNIQUE(wilayah_id, id_feature)");
    echo "✓ Composite constraint added\n";
} catch (\Exception $e) {
    echo "Note: Composite constraint might already exist: " . $e->getMessage() . "\n";
}

echo "\n✓ Done!\n";
