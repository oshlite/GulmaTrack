<?php
/**
 * Fix missing import_log_id values
 * Run from command line: php fix_import_logs.php
 */

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DataGulma;
use App\Models\ImportLog;

echo "🔧 Starting to fix missing import_log_id values...\n";
echo "=".str_repeat("=", 50)."\n";

// Get all ImportLogs ordered by creation
$imports = ImportLog::orderBy('created_at', 'asc')->get();

$fixed = 0;
$skipped = 0;

foreach ($imports as $import) {
    // Get wilayah list from import
    $wilayahArray = explode(',', $import->wilayah_id);
    
    // Count records in this wilayah range that don't have import_log_id
    $orphanCount = DataGulma::whereIn('wilayah_id', $wilayahArray)
        ->whereNull('import_log_id')
        ->count();
    
    if ($orphanCount > 0) {
        // Assign these orphan records to this import
        $updated = DataGulma::whereIn('wilayah_id', $wilayahArray)
            ->whereNull('import_log_id')
            ->update(['import_log_id' => $import->id]);
        
        $fixed += $updated;
        echo "✅ ImportLog {$import->id} ({$import->nama_file}): Fixed {$updated} orphan records\n";
    } else {
        $skipped++;
        echo "⏭️  ImportLog {$import->id}: No orphans found\n";
    }
}

// Check for any remaining orphans
$remainingOrphans = DataGulma::whereNull('import_log_id')->count();

echo "=".str_repeat("=", 50)."\n";
echo "📊 Summary:\n";
echo "   ✅ Fixed records: {$fixed}\n";
echo "   ⏭️  Skipped: {$skipped}\n";
echo "   ⚠️  Remaining orphans: {$remainingOrphans}\n";
echo "   📦 Total imports: ".$imports->count()."\n";
echo "=".str_repeat("=", 50)."\n";

// Verify import 16 now has data
$import16Data = DataGulma::where('import_log_id', 16)->count();
echo "\n🔍 Verification: Import 16 now has {$import16Data} records\n";

echo "\n✨ Done!\n";
