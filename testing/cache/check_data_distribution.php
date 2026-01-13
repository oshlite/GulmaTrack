<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DataGulma;
use App\Models\ImportLog;

echo "🔍 Checking where data actually is...\n";
echo "=".str_repeat("=", 50)."\n";

// Check import_log_id values in database
$imports = ImportLog::select('id', 'nama_file', 'tahun', 'bulan', 'minggu', 'wilayah_id', 'jumlah_berhasil')
    ->orderBy('id', 'desc')
    ->get();

foreach ($imports as $import) {
    $actualCount = DataGulma::where('import_log_id', $import->id)->count();
    $status = $actualCount > 0 ? "✅" : "❌";
    echo "$status Import {$import->id}: {$import->nama_file} | Expected: {$import->jumlah_berhasil} | Actual: {$actualCount}\n";
}

echo "=".str_repeat("=", 50)."\n";

// Check for null import_log_id
$nullCount = DataGulma::whereNull('import_log_id')->count();
echo "\n⚠️  Records with NULL import_log_id: {$nullCount}\n";

// Show distribution
echo "\n📊 Import_log_id distribution:\n";
$distribution = DataGulma::select('import_log_id', \DB::raw('count(*) as count'))
    ->groupBy('import_log_id')
    ->orderBy('import_log_id', 'desc')
    ->get();

foreach ($distribution as $dist) {
    $id = $dist->import_log_id ?? "NULL";
    echo "   import_log_id = {$id}: {$dist->count} records\n";
}
