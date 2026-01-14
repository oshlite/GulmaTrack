<?php
/**
 * Direct Import dengan file gulma.csv
 * Run: php artisan tinker < reimport_final.php
 */

// Step 1: Delete old data
$importLog = \App\Models\ImportLog::where('tahun', 2025)
    ->where('bulan', 12)
    ->where('minggu', 4)
    ->latest()
    ->first();

if ($importLog) {
    $deleted = \App\Models\DataGulma::where('import_log_id', $importLog->id)->delete();
    echo "✓ Deleted {$deleted} old records\n";
    $importLog->delete();
    echo "✓ Deleted old import log\n";
}

// Step 2: Create new import log
$newLog = \App\Models\ImportLog::create([
    'nama_file' => 'gulma.csv (REIMPORTED)',
    'wilayah_id' => '16,17,18,19,20,21,22,23',
    'tahun' => 2025,
    'bulan' => 12,
    'minggu' => 4,
    'jumlah_records' => 0,
    'jumlah_berhasil' => 0,
    'jumlah_gagal' => 0,
    'status' => 'pending',
    'user_id' => 1
]);
echo "✓ Created import log ID: {$newLog->id}\n";

// Step 3: Read and import CSV
$csvFile = storage_path('../testing/dataa/sample-data/gulma.csv');
echo "Reading CSV: {$csvFile}\n";

if (!file_exists($csvFile)) {
    echo "❌ File not found!\n";
    exit(1);
}

$csv = array_map('str_getcsv', file($csvFile));
$headers = array_shift($csv);
$headers = array_map('trim', $headers);
$headers = array_map('strtolower', $headers);

echo "Headers: " . implode(', ', $headers) . "\n";

$berhasil = 0;
$debug_count = 0;

foreach ($csv as $row) {
    if (empty(array_filter($row))) continue;

    $data = array_combine($headers, $row);
    $data = array_map('trim', $data);

    $wilayah_id = (int) ($data['wilayah'] ?? 0);
    if ($wilayah_id < 16 || $wilayah_id > 23) continue;

    // Parse nilai dengan BENAR
    $total_tk_raw = $data['total tk'] ?? null;
    $total_tk_value = null;
    if (!empty($total_tk_raw) && is_numeric($total_tk_raw)) {
        $total_tk_value = (float) $total_tk_raw;
    }

    // DEBUG: First 3 Wilayah 16
    if ($wilayah_id == 16 && $debug_count < 3) {
        echo "\nDEBUG #{$debug_count}:\n";
        echo "  - Seksi: {$data['seksi']}\n";
        echo "  - Total TK raw: '{$total_tk_raw}'\n";
        echo "  - Total TK parsed: {$total_tk_value}\n";
        $debug_count++;
    }

    \App\Models\DataGulma::updateOrCreate(
        [
            'wilayah_id' => $wilayah_id,
            'id_feature' => $data['seksi'],
        ],
        [
            'pg' => $data['pg'] ?? null,
            'fm' => $data['fm'] ?? null,
            'seksi' => $data['seksi'] ?? null,
            'neto' => !empty($data['neto']) ? (float) $data['neto'] : null,
            'hasil' => !empty($data['hasil']) ? (float) $data['hasil'] : null,
            'umur_tanaman' => !empty($data['umur tanaman']) ? (int) $data['umur tanaman'] : null,
            'penanggungjawab' => $data['penanggungjawab'] ?? null,
            'kode_aktf' => $data['kode aktf'] ?? null,
            'activitas' => $data['activitas'] ?? null,
            'kategori' => $data['kategori'] ?? null,
            'tk_ha' => !empty($data['tk/ha']) ? (float) $data['tk/ha'] : null,
            'total_tk' => $total_tk_value,
            'tanggal' => date('Y-m-d'),
            'import_log_id' => $newLog->id
        ]
    );

    $berhasil++;
}

echo "\n=== RESULT ===\n";
echo "✓ Imported: {$berhasil} records\n";

// Verify
$wil16 = \App\Models\DataGulma::where('import_log_id', $newLog->id)
    ->where('wilayah_id', 16)
    ->get();

echo "\nWilayah 16:\n";
echo "  - Count: {$wil16->count()}\n";
echo "  - Sum total_tk: " . $wil16->sum('total_tk') . "\n";
echo "  - First 3:\n";

$wil16->take(3)->each(function($r) {
    echo "    Seksi: {$r->seksi}, Total TK: {$r->total_tk}\n";
});

echo "\n✓ DONE! Reload statistik page\n";
