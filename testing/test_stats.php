<?php
require_once 'bootstrap/app.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$wilayahId = 23;
$importId = 36;

$data = \App\Models\DataGulma::where('wilayah_id', $wilayahId)
    ->where('import_log_id', $importId)
    ->get();

echo "=== WILAYAH 23, IMPORT_ID 36 ===\n";
echo "Total records: " . $data->count() . "\n\n";

$byKat = $data->groupBy('kategori');
foreach ($byKat as $kat => $items) {
    $kategoriDisplay = $kat ?: '(empty)';
    echo "Kategori '$kategoriDisplay': " . $items->count() . " records\n";
    foreach ($items->take(3) as $idx => $item) {
        echo "  [" . ($idx + 1) . "] Lokasi: {$item->seksi}\n";
    }
    echo "\n";
}

// Also show normalized groups (deduplicated)
echo "\n=== DEDUPLICATED BY LOKASI ===\n";
$deduped = [];
$kategoriValue = ['bersih' => 1, 'ringan' => 2, 'sedang' => 3, 'berat' => 4];

foreach ($data as $record) {
    $normalizedSeksi = strtolower(trim($record->seksi));
    
    if (!isset($deduped[$normalizedSeksi])) {
        $deduped[$normalizedSeksi] = (object)[
            'kategori' => ucfirst(strtolower($record->kategori ?? '')),
            'count' => 1
        ];
    } else {
        // Keep BEST kategori
        $existing = $deduped[$normalizedSeksi];
        $dataValue = $kategoriValue[strtolower($record->kategori ?? 'berat')] ?? 5;
        $existingValue = $kategoriValue[strtolower($existing->kategori ?? 'berat')] ?? 5;
        
        if ($dataValue < $existingValue) {
            $existing->kategori = ucfirst(strtolower($record->kategori ?? ''));
        }
        $existing->count++;
    }
}

echo "Deduplicated lokasi: " . count($deduped) . "\n";
$byKatDedup = [];
foreach ($deduped as $lokasi => $data) {
    if (!isset($byKatDedup[$data->kategori])) {
        $byKatDedup[$data->kategori] = [];
    }
    $byKatDedup[$data->kategori][] = $lokasi;
}

foreach ($byKatDedup as $kat => $lokasis) {
    $kategoriDisplay = $kat ?: '(empty)';
    echo "Kategori '$kategoriDisplay': " . count($lokasis) . " lokasi\n";
    foreach (array_slice($lokasis, 0, 3) as $lok) {
        echo "  - $lok\n";
    }
    echo "\n";
}
?>
