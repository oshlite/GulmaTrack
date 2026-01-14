<?php
/**
 * Test AdminController upload logic
 * Simulate CSV upload dari dashboard
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DataGulma;
use App\Models\ImportLog;
use Illuminate\Support\Facades\DB;

echo "=== TEST ADMIN UPLOAD LOGIC ===\n\n";

// Simulate CSV content (format baru sesuai user spec)
$csvContent = "PG,FM,WIL,SEKSI,NETO,HASIL,uMUR,TNM_STS,ACTIVITAS,KATEGORI,TANGGAL,TK/HA,TOTAL_TK
3,5,16,506C1,7.36,7.36,10.9,Normal,Weeding manual,Bersih,2025-12-01,4,29.44
3,5,16,506C2,10.88,10.88,10.5,Normal,Weeding manual,Ringan,2025-12-02,4,43.52
3,5,16,505B,13.05,13.05,14.2,Normal,Weeding manual,Ringan,2025-12-03,4,52.2
3,5,16,505F,7.31,7.31,12.1,Normal,Weeding manual,Ringan,2025-12-04,4,29.24
3,5,16,506B1,11.28,11.28,16.3,Normal,Weeding manual,Ringan,2025-12-05,4,45.12";

// Save to temp CSV
$tempCsvPath = __DIR__ . '/temp_upload_test.csv';
file_put_contents($tempCsvPath, $csvContent);

echo "✓ CSV file created: $tempCsvPath\n";

// Parse CSV like AdminController does
$csv = array_map('str_getcsv', file($tempCsvPath));
$header = array_shift($csv);
$headers = array_map('strtolower', $header);
$headers = array_map('trim', $headers);

echo "✓ Headers: " . implode(', ', $headers) . "\n";

// Simulate ImportLog create
$importLog = ImportLog::create([
    'nama_file' => 'test_upload.csv',
    'wilayah_id' => '16',
    'tahun' => 2025,
    'bulan' => 12,
    'minggu' => 1,
    'jumlah_records' => 0,
    'jumlah_berhasil' => 0,
    'jumlah_gagal' => 0,
    'status' => 'pending',
    'user_id' => 1
]);

echo "✓ ImportLog created with ID: " . $importLog->id . "\n\n";

// Clear existing data
DataGulma::where('import_log_id', $importLog->id)->delete();

$berhasil = 0;
$gagal = 0;
$errors = [];

// Helper functions (like AdminController)
$parseFloat = function($val) {
    if (empty($val) || !is_numeric($val)) return null;
    return (float) $val;
};

$parseDate = function($val) {
    if (empty($val)) return null;
    try {
        $date = \DateTime::createFromFormat('Y-m-d', $val);
        if ($date === false) $date = \DateTime::createFromFormat('d-m-Y', $val);
        if ($date === false) $date = \DateTime::createFromFormat('d/m/Y', $val);
        return $date ? $date->format('Y-m-d') : null;
    } catch (\Exception $e) {
        return null;
    }
};

// Process each row
foreach ($csv as $index => $row) {
    if (empty(array_filter($row))) continue;

    try {
        $data = array_combine($headers, $row);
        $data = array_map('trim', $data);

        if (empty($data['seksi'])) {
            throw new \Exception('SEKSI kosong');
        }

        $rowWilayahId = !empty($data['wil']) ? (int) $data['wil'] : null;
        
        if (!$rowWilayahId || $rowWilayahId < 16 || $rowWilayahId > 23) {
            throw new \Exception('Wilayah tidak valid: ' . ($data['wil'] ?? 'kosong'));
        }

        $idFeature = $data['seksi'];

        DataGulma::create([
            'wilayah_id' => $rowWilayahId,
            'id_feature' => $idFeature,
            'import_log_id' => $importLog->id,
            'pg' => $data['pg'] ?? null,
            'fm' => $data['fm'] ?? null,
            'seksi' => $data['seksi'] ?? null,
            'neto' => $parseFloat($data['neto'] ?? null),
            'hasil' => $parseFloat($data['hasil'] ?? null),
            'umur' => $parseFloat($data['umur'] ?? $data['umur tanaman'] ?? null),
            'tnm_sts' => $data['tnm_sts'] ?? $data['tnm sts'] ?? null,
            'activitas' => $data['activitas'] ?? null,
            'kategori' => $data['kategori'] ?? null,
            'tanggal' => $parseDate($data['tanggal'] ?? null) ?? now()->toDateString(),
            'tk_ha' => $parseFloat($data['tk/ha'] ?? null),
            'total_tk' => $parseFloat($data['total tk'] ?? null),
        ]);

        $berhasil++;
    } catch (\Exception $e) {
        $gagal++;
        $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
        echo "❌ Error baris " . ($index + 2) . ": " . $e->getMessage() . "\n";
    }
}

// Update import log
$importLog->update([
    'jumlah_records' => $berhasil + $gagal,
    'jumlah_berhasil' => $berhasil,
    'jumlah_gagal' => $gagal,
    'status' => $gagal === 0 ? 'success' : 'failed',
    'error_log' => !empty($errors) ? json_encode($errors) : null
]);

echo "\n=== RESULT ===\n";
echo "✓ Berhasil: $berhasil\n";
echo "❌ Gagal: $gagal\n";
echo "📊 Total: " . ($berhasil + $gagal) . "\n\n";

// Verify data in database
$total_tk = DataGulma::where('wilayah_id', 16)->where('import_log_id', $importLog->id)->sum('total_tk');
echo "=== DATA VERIFICATION ===\n";
echo "Wilayah 16 - Total TK: " . number_format($total_tk, 2, ',', '.') . " Orang\n";

// Show sample record
$sample = DataGulma::where('wilayah_id', 16)->where('import_log_id', $importLog->id)->first();
if ($sample) {
    echo "\nSample record:\n";
    echo "- SEKSI: " . $sample->seksi . "\n";
    echo "- NETO: " . $sample->neto . "\n";
    echo "- HASIL: " . $sample->hasil . "\n";
    echo "- uMUR: " . $sample->umur . "\n";
    echo "- TNM_STS: " . $sample->tnm_sts . "\n";
    echo "- ACTIVITAS: " . $sample->activitas . "\n";
    echo "- KATEGORI: " . $sample->kategori . "\n";
    echo "- TANGGAL: " . $sample->tanggal . "\n";
    echo "- TK/HA: " . $sample->tk_ha . "\n";
    echo "- TOTAL_TK: " . $sample->total_tk . "\n";
}

// Cleanup
unlink($tempCsvPath);
echo "\n✓ Temp file deleted\n";
