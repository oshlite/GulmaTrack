<?php
/**
 * FINAL FIX: Delete old data + Re-import CSV dengan logic yang BENAR
 * Run: php REIMPORT_NOW.php
 */

require __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DataGulma;
use App\Models\ImportLog;

echo "=== CLEANING UP OLD DATA ===\n";

// Delete SEMUA data dari import 2025/12/minggu4
$importLog = ImportLog::where('tahun', 2025)
    ->where('bulan', 12)
    ->where('minggu', 4)
    ->latest()
    ->first();

if ($importLog) {
    $deleted = DataGulma::where('import_log_id', $importLog->id)->delete();
    echo "✓ Deleted {$deleted} old records from import_log_id {$importLog->id}\n";
    $importLog->delete();
    echo "✓ Deleted import log\n";
} else {
    echo "ℹ No import log found for 2025/12/minggu4\n";
}

echo "\n=== RE-IMPORTING CSV ===\n";

$csvFile = __DIR__ . '/../../testing/dataa/sample-data/gulma.csv';

if (!file_exists($csvFile)) {
    die("❌ File gulma.csv tidak ditemukan di: {$csvFile}\n");
}

echo "Reading CSV: {$csvFile}\n";

$csv = array_map('str_getcsv', file($csvFile));

// Get headers dan trim SPASI
$headers = array_shift($csv);
$headers = array_map('strtolower', $headers);
$headers = array_map('trim', $headers);  // 🔑 PENTING: Trim spasi dari header

echo "Headers found: " . implode(', ', $headers) . "\n";

// Create new import log
$importLog = ImportLog::create([
    'nama_file' => 'gulma.csv (FIXED)',
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

echo "Created import log ID: {$importLog->id}\n\n";

$berhasil = 0;
$gagal = 0;
$errors = [];

// Helper functions
$parseFloat = function($val) {
    if (empty($val) || !is_numeric($val)) return null;
    return (float) $val;
};

$parseInt = function($val) {
    if (empty($val) || !is_numeric($val)) return null;
    return (int) $val;
};

foreach ($csv as $index => $row) {
    if (empty(array_filter($row))) continue;

    try {
        $data = array_combine($headers, $row);
        $data = array_map('trim', $data);

        if (empty($data['seksi'])) {
            throw new \Exception('SEKSI kosong');
        }

        $wilayahId = (int) ($data['wilayah'] ?? 0);
        if ($wilayahId < 16 || $wilayahId > 23) {
            throw new \Exception("Wilayah tidak valid: {$wilayahId}");
        }

        // DEBUG: First 3 rows untuk Wilayah 16
        if ($wilayahId == 16 && $berhasil < 3) {
            echo "DEBUG Row {$berhasil}:\n";
            echo "  - seksi: " . ($data['seksi'] ?? 'NULL') . "\n";
            echo "  - tk/ha: " . ($data['tk/ha'] ?? 'NULL') . "\n";
            echo "  - total tk RAW: " . ($data['total tk'] ?? 'NULL') . "\n";
            echo "  - parsed float: " . ($parseFloat($data['total tk'] ?? null) ?? 'NULL') . "\n";
        }

        DataGulma::updateOrCreate(
            [
                'wilayah_id' => $wilayahId,
                'id_feature' => $data['seksi'],
            ],
            [
                'pg' => $data['pg'] ?? null,
                'fm' => $data['fm'] ?? null,
                'seksi' => $data['seksi'] ?? null,
                'neto' => $parseFloat($data['neto'] ?? null),
                'hasil' => $parseFloat($data['hasil'] ?? null),
                'umur_tanaman' => $parseInt($data['umur tanaman'] ?? null),
                'penanggungjawab' => $data['penanggungjawab'] ?? null,
                'kode_aktf' => $data['kode aktf'] ?? null,
                'activitas' => $data['activitas'] ?? null,
                'kategori' => $data['kategori'] ?? null,
                'tk_ha' => $parseFloat($data['tk/ha'] ?? null),
                'total_tk' => $parseFloat($data['total tk'] ?? null),  // ✅ BENAR!
                'tanggal' => date('Y-m-d'),
                'import_log_id' => $importLog->id
            ]
        );

        $berhasil++;

        if ($berhasil % 50 == 0) {
            echo "Progress: {$berhasil} records imported\n";
        }
    } catch (\Exception $e) {
        $gagal++;
        $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
    }
}

// Update import log
$importLog->update([
    'jumlah_records' => $berhasil + $gagal,
    'jumlah_berhasil' => $berhasil,
    'jumlah_gagal' => $gagal,
    'status' => $gagal === 0 ? 'success' : 'partial'
]);

echo "\n=== HASIL ===\n";
echo "✓ Berhasil: {$berhasil}\n";
echo "✗ Gagal: {$gagal}\n";
echo "Total: " . ($berhasil + $gagal) . "\n";

// Verify: Check Wilayah 16 data
echo "\n=== VERIFICATION ===\n";
$wil16Data = DataGulma::where('import_log_id', $importLog->id)
    ->where('wilayah_id', 16)
    ->get();

echo "Wilayah 16 records: {$wil16Data->count()}\n";
echo "Total TK sum: " . $wil16Data->sum('total_tk') . "\n";
echo "\nFirst 3 records:\n";

$wil16Data->take(3)->each(function($r) {
    echo "  Seksi: {$r->seksi}, Total TK: {$r->total_tk}\n";
});

echo "\n✓ SELESAI! Cek statistik page sekarang - harus 473.88!\n";
