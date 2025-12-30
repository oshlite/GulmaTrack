<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DataGulma;
use App\Models\ImportLog;
use Illuminate\Support\Facades\DB;

echo "=== RE-IMPORT GULMA.CSV ===\n\n";

// Hapus data lama yang tidak lengkap (yang tidak punya data pg, fm, dll)
echo "Menghapus data lama yang tidak lengkap...\n";
$deleted = DataGulma::whereNull('pg')->orWhereNull('kategori')->delete();
echo "✓ Dihapus: $deleted records\n\n";

// Import ulang dari CSV
$csvFile = __DIR__ . '/gulma.csv';
if (!file_exists($csvFile)) {
    die("File gulma.csv tidak ditemukan!\n");
}

echo "Membaca file CSV...\n";
$csv = array_map('str_getcsv', file($csvFile));
$headers = array_shift($csv);
$headers = array_map('strtolower', $headers);
$headers = array_map('trim', $headers);

echo "Headers: " . implode(', ', $headers) . "\n\n";

// Collect all wilayah
$wilayahIndex = array_search('wilayah', $headers);
$allWilayah = [];
foreach ($csv as $row) {
    if (!empty($row[$wilayahIndex])) {
        $wil = (int) trim($row[$wilayahIndex]);
        if ($wil >= 16 && $wil <= 23) {
            $allWilayah[$wil] = true;
        }
    }
}

$wilayahList = implode(',', array_keys($allWilayah));
echo "Wilayah yang ditemukan: $wilayahList\n";
echo "Total wilayah: " . count($allWilayah) . "\n\n";

// Create import log
$importLog = ImportLog::create([
    'nama_file' => 'gulma.csv',
    'wilayah_id' => $wilayahList,
    'bulan' => 12,
    'minggu' => 4,
    'jumlah_records' => 0,
    'jumlah_berhasil' => 0,
    'jumlah_gagal' => 0,
    'status' => 'pending',
    'user_id' => 1 // Admin user
]);

echo "Import log created: ID {$importLog->id}\n\n";

$berhasil = 0;
$gagal = 0;
$errors = [];

echo "Memproses data...\n";

foreach ($csv as $index => $row) {
    if (empty(array_filter($row))) continue;

    try {
        $data = array_combine($headers, $row);
        $data = array_map('trim', $data);

        if (empty($data['seksi'])) {
            throw new \Exception('SEKSI kosong');
        }

        $rowWilayahId = !empty($data['wilayah']) ? (int) $data['wilayah'] : null;
        
        if (!$rowWilayahId || $rowWilayahId < 16 || $rowWilayahId > 23) {
            throw new \Exception('Wilayah tidak valid: ' . ($data['wilayah'] ?? 'kosong'));
        }

        $idFeature = $data['seksi'];

        $parseFloat = function($val) {
            if (empty($val) || !is_numeric($val)) return null;
            return (float) $val;
        };
        
        $parseInt = function($val) {
            if (empty($val) || !is_numeric($val)) return null;
            return (int) $val;
        };

        DataGulma::updateOrCreate(
            [
                'wilayah_id' => $rowWilayahId,
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
                'tanggal' => now()->toDateString(),
                'import_log_id' => $importLog->id
            ]
        );

        $berhasil++;
        
        if ($berhasil % 100 == 0) {
            echo "  Processed: $berhasil records\n";
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
    'status' => $gagal === 0 ? 'success' : 'failed',
    'error_log' => !empty($errors) ? json_encode($errors) : null
]);

echo "\n=== HASIL ===\n";
echo "Berhasil: $berhasil\n";
echo "Gagal: $gagal\n";
echo "\nData per wilayah:\n";

$dataPerWilayah = DataGulma::selectRaw('wilayah_id, COUNT(*) as total')
    ->whereNotNull('kategori')
    ->groupBy('wilayah_id')
    ->orderBy('wilayah_id')
    ->get();

foreach ($dataPerWilayah as $d) {
    echo "  Wilayah {$d->wilayah_id}: {$d->total} records\n";
}

echo "\n=== SELESAI ===\n";
