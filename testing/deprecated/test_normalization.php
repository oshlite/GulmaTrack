<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DataGulma;

echo "=== TESTING NORMALIZATION LOGIC ===\n\n";

// Get database seksi for wilayah 16, latest import
$latestImport = DB::table('import_logs')
    ->where('wilayah_id', 'LIKE', '%16%')
    ->where('status', 'success')
    ->orderBy('id', 'desc')
    ->first();

echo "Latest import ID: {$latestImport->id}\n\n";

// Get data from database
$gulmaData = DataGulma::where('wilayah_id', 16)
    ->where('import_log_id', $latestImport->id)
    ->get();

echo "Database records for import #{$latestImport->id}: " . $gulmaData->count() . "\n\n";

// Create normalized map
$gulmaMap = [];
foreach ($gulmaData as $data) {
    $normalized = strtolower(trim($data->seksi));
    $gulmaMap[$normalized] = $data;
}

echo "Sample normalized seksi from DB:\n";
foreach (array_slice(array_keys($gulmaMap), 0, 10) as $key) {
    echo "  '$key'\n";
}

// Get GeoJSON
$geojsonPath = base_path('dataya/Wil16.geojson');
$geojson = json_decode(file_get_contents($geojsonPath), true);

echo "\nSample Lokasi from GeoJSON:\n";
$geoSeksi = [];
foreach (array_slice($geojson['features'], 0, 10) as $feature) {
    $lokasi = $feature['properties']['Lokasi'] ?? 'N/A';
    $normalized = strtolower(trim($lokasi));
    echo "  '$lokasi' => '$normalized'\n";
    $geoSeksi[] = $normalized;
}

// Check matches
echo "\n=== CHECKING MATCHES ===\n";
$matches = 0;
foreach ($geoSeksi as $geo) {
    if (isset($gulmaMap[$geo])) {
        echo "✓ Match found: '$geo' => kategori: {$gulmaMap[$geo]->kategori}\n";
        $matches++;
    } else {
        echo "✗ No match: '$geo'\n";
    }
}

echo "\nTotal matches: $matches / " . count($geoSeksi) . "\n";
