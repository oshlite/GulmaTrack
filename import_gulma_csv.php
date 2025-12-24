<?php
/**
 * Script untuk import CSV gulma.csv ke database
 * Run: php import_gulma_csv.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DataGulma;
use App\Models\ImportLog;

$csvFile = __DIR__ . '/gulma.csv';

if (!file_exists($csvFile)) {
    die("File gulma.csv tidak ditemukan!\n");
}

echo "Membaca file CSV...\n";

$csv = array_map('str_getcsv', file($csvFile));

// Get headers
$headers = array_shift($csv);
$headers = array_map('strtolower', $headers);
$headers = array_map('trim', $headers);

echo "Headers: " . implode(', ', $headers) . "\n";

// Get wilayah dari baris pertama
$wilayahIndex = array_search('wilayah', $headers);
$wilayahId = null;
if (!empty($csv[0][$wilayahIndex])) {
    $wilayahId = (int) trim($csv[0][$wilayahIndex]);
}

echo "Wilayah ID: $wilayahId\n";

if (!$wilayahId || $wilayahId < 16 || $wilayahId > 23) {
    die("Wilayah tidak valid. Harus antara 16-23\n");
}

// Create import log
$importLog = ImportLog::create([
    'nama_file' => 'gulma.csv',
    'wilayah_id' => $wilayahId,
    'jumlah_records' => 0,
    'jumlah_berhasil' => 0,
    'jumlah_gagal' => 0,
    'status' => 'pending',
    'user_id' => 1 // Admin
]);

$berhasil = 0;
$gagal = 0;
$errors = [];

echo "Memproses " . count($csv) . " baris data...\n";

// Process each row
foreach ($csv as $index => $row) {
    if (empty(array_filter($row))) continue;

    try {
        $data = array_combine($headers, $row);
        $data = array_map('trim', $data);

        // Validation basic
        if (empty($data['seksi'])) {
            throw new \Exception('SEKSI kosong');
        }

        // id_feature langsung dari SEKSI
        $idFeature = $data['seksi'];

        // Parse numeric values
        $parseFloat = function($val) {
            if (empty($val) || !is_numeric($val)) return null;
            return (float) $val;
        };
        
        $parseInt = function($val) {
            if (empty($val) || !is_numeric($val)) return null;
            return (int) $val;
        };

        // Save to database
        DataGulma::updateOrCreate(
            [
                'wilayah_id' => $wilayahId,
                'id_feature' => $idFeature,
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
                'total_tk' => $parseInt($data['total tk'] ?? null),
                'tanggal' => date('Y-m-d'),
                'import_log_id' => $importLog->id
            ]
        );

        $berhasil++;
        
        if ($berhasil % 50 == 0) {
            echo "Progress: $berhasil berhasil, $gagal gagal\n";
        }
    } catch (\Exception $e) {
        $gagal++;
        $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
        
        if ($gagal <= 10) {
            echo "Error baris " . ($index + 2) . ": " . $e->getMessage() . "\n";
        }
    }
}

// Update import log
$importLog->update([
    'jumlah_records' => $berhasil + $gagal,
    'jumlah_berhasil' => $berhasil,
    'jumlah_gagal' => $gagal,
    'status' => $gagal === 0 ? 'success' : 'partial',
    'error_log' => !empty($errors) ? json_encode($errors) : null
]);

echo "\n=== SELESAI ===\n";
echo "Berhasil: $berhasil\n";
echo "Gagal: $gagal\n";
echo "Total: " . ($berhasil + $gagal) . "\n";

if ($gagal > 0 && $gagal <= 10) {
    echo "\nError yang terjadi:\n";
    foreach ($errors as $error) {
        echo "- $error\n";
    }
}
