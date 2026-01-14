<?php
/**
 * Import CSV dengan format BENAR
 * Format: PG,FM,WIL,SEKSI,NETO,HASIL,uMUR,TNM_STS,ACTIVITAS,KATEGORI,TANGGAL,TK/HA,TOTAL_TK
 * 
 * Aturan:
 * - Jika kolom kosong, gunakan "-"
 * - NETO, HASIL, uMUR, TK/HA, TOTAL_TK harus numeric dengan decimal
 * 
 * Run: php artisan tinker
 *      require('testing/migration/import_correct.php');
 */

require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DataGulma;
use App\Models\ImportLog;
use Illuminate\Support\Facades\DB;

function importCSV($csvFile) {
    echo "=== IMPORT CSV GULMA ===\n";
    
    if (!file_exists($csvFile)) {
        echo "❌ File tidak ditemukan: $csvFile\n";
        return false;
    }

    echo "✓ File ditemukan: $csvFile\n";
    
    // Read CSV
    $lines = file($csvFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $header = str_getcsv(array_shift($lines));
    
    echo "✓ Header: " . implode(', ', $header) . "\n";
    echo "✓ Total baris data: " . count($lines) . "\n\n";

    // Parse helper
    $parseFloat = function($val) {
        $val = trim($val ?? '');
        if (empty($val) || $val === '-') return null;
        $num = (float) str_replace(',', '.', $val);
        return is_nan($num) ? null : $num;
    };

    $parseInt = function($val) {
        $val = trim($val ?? '');
        if (empty($val) || $val === '-') return null;
        $num = (int) $val;
        return $num;
    };

    $parseString = function($val) {
        $val = trim($val ?? '');
        return (empty($val) || $val === '-') ? null : $val;
    };

    $parseDate = function($val) {
        $val = trim($val ?? '');
        if (empty($val) || $val === '-') return null;
        try {
            $date = \DateTime::createFromFormat('Y-m-d', $val);
            if ($date === false) $date = \DateTime::createFromFormat('d-m-Y', $val);
            if ($date === false) $date = \DateTime::createFromFormat('d/m/Y', $val);
            return $date ? $date->format('Y-m-d') : null;
        } catch (\Exception $e) {
            return null;
        }
    };

    // Clear existing data
    DB::table('data_gulma')->truncate();
    echo "✓ Tabel data_gulma dikosongkan\n\n";

    $berhasil = 0;
    $gagal = 0;
    $errors = [];

    // Process each row
    foreach ($lines as $idx => $line) {
        $row = str_getcsv($line);
        
        if (count($row) < count($header)) {
            // Pad missing columns with empty strings
            $row = array_pad($row, count($header), '');
        }

        $data = array_combine($header, $row);

        try {
            // Get WIL (wilayah_id)
            $wil = $data['WIL'] ?? null;
            if (!$wil || !is_numeric($wil)) {
                throw new \Exception("WIL (wilayah) tidak valid: '$wil'");
            }
            $wilayahId = (int) $wil;
            if ($wilayahId < 16 || $wilayahId > 23) {
                throw new \Exception("WIL harus 16-23, dapat: $wilayahId");
            }

            // Get SEKSI (id_feature)
            $seksi = $parseString($data['SEKSI']);
            if (!$seksi) {
                throw new \Exception("SEKSI tidak boleh kosong");
            }

            // Parse numeric fields
            $neto = $parseFloat($data['NETO'] ?? '');
            $hasil = $parseFloat($data['HASIL'] ?? '');
            $umur = $parseFloat($data['uMUR'] ?? ''); // Note: lowercase 'uMUR'
            $tkHa = $parseFloat($data['TK/HA'] ?? '');
            $totalTk = $parseFloat($data['TOTAL_TK'] ?? '');

            // Parse string fields
            $pg = $parseString($data['PG']);
            $fm = $parseString($data['FM']);
            $tnmSts = $parseString($data['TNM_STS']);
            $activitas = $parseString($data['ACTIVITAS']);
            $kategori = $parseString($data['KATEGORI']);
            $tanggal = $parseDate($data['TANGGAL']);

            // Create/update record
            DataGulma::updateOrCreate(
                [
                    'wilayah_id' => $wilayahId,
                    'id_feature' => $seksi,
                ],
                [
                    'pg' => $pg,
                    'fm' => $fm,
                    'seksi' => $seksi,
                    'neto' => $neto,
                    'hasil' => $hasil,
                    'umur' => $umur,
                    'tnm_sts' => $tnmSts,
                    'activitas' => $activitas,
                    'kategori' => $kategori,
                    'tanggal' => $tanggal,
                    'tk_ha' => $tkHa,
                    'total_tk' => $totalTk,
                ]
            );

            $berhasil++;

            if ($berhasil % 50 == 0) {
                echo "Progress: $berhasil berhasil, $gagal gagal\n";
            }

        } catch (\Exception $e) {
            $gagal++;
            $errors[] = "Baris " . ($idx + 2) . ": " . $e->getMessage();
            
            if ($gagal <= 10) {
                echo "⚠️  Error baris " . ($idx + 2) . ": " . $e->getMessage() . "\n";
            }
        }
    }

    echo "\n=== SELESAI ===\n";
    echo "✓ Berhasil: $berhasil\n";
    echo "❌ Gagal: $gagal\n";
    echo "📊 Total: " . ($berhasil + $gagal) . "\n\n";

    // Show summary by wilayah
    echo "=== SUMMARY BY WILAYAH ===\n";
    $summary = DB::table('data_gulma')
        ->selectRaw('wilayah_id, COUNT(*) as count, SUM(total_tk) as total_tk_sum')
        ->groupBy('wilayah_id')
        ->orderBy('wilayah_id')
        ->get();

    foreach ($summary as $row) {
        $total = $row->total_tk_sum ? number_format($row->total_tk_sum, 2, ',', '.') : '0.00';
        echo "Wilayah " . $row->wilayah_id . ": " . $row->count . " records, Total TK = " . $total . " Orang\n";
    }

    return true;
}

// Main execution
$csvFile = __DIR__ . '/gulma.csv';
importCSV($csvFile);
