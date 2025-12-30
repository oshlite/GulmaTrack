<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DataGulma;
use App\Models\MapPublication;

echo "=== GUEST MODE SIMULATION ===\n\n";

// Check published map
$published = MapPublication::getLatestPublished();
if ($published) {
    echo "Published map found:\n";
    echo "  ID: {$published->id}\n";
    echo "  Import Log ID: {$published->import_log_id}\n";
    echo "  Published at: {$published->published_at}\n\n";
    
    // Query data like API does for guest
    $query = DataGulma::where('wilayah_id', 16);
    $query->where('import_log_id', $published->import_log_id);
    
    $gulmaData = $query->get();
    echo "Data for wilayah 16, import {$published->import_log_id}: " . $gulmaData->count() . " records\n\n";
    
    if ($gulmaData->count() > 0) {
        echo "Sample data:\n";
        foreach ($gulmaData->take(5) as $data) {
            echo sprintf(
                "  Seksi: %s | Kategori: %s | PG: %s | FM: %s\n",
                $data->seksi,
                $data->kategori ?? 'NULL',
                $data->pg,
                $data->fm
            );
        }
    }
} else {
    echo "No published map found!\n";
}

echo "\n=== ADMIN MODE SIMULATION ===\n\n";

// Query without import_log_id filter
$query = DataGulma::where('wilayah_id', 16);
// Get latest for each seksi
$latestImportId = DB::table('import_logs')
    ->where('wilayah_id', 'LIKE', '%16%')
    ->where('status', 'success')
    ->orderBy('id', 'desc')
    ->value('id');

echo "Latest import ID: $latestImportId\n";
$query->where('import_log_id', $latestImportId);

$gulmaData = $query->get();
echo "Data for wilayah 16, latest import: " . $gulmaData->count() . " records\n\n";

if ($gulmaData->count() > 0) {
    echo "Sample data:\n";
    foreach ($gulmaData->take(5) as $data) {
        echo sprintf(
            "  Seksi: %s | Kategori: %s | PG: %s | FM: %s\n",
            $data->seksi,
            $data->kategori ?? 'NULL',
            $data->pg,
            $data->fm
        );
    }
}
