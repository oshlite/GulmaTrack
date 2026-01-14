<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DataGulma;
use App\Models\ImportLog;
use App\Models\MapPublication;
use Illuminate\Support\Facades\Log;
use DB;

class SimulateUpload extends Command
{
    protected $signature = 'test:simulate-upload';
    protected $description = 'Simulate CSV upload to test the parsing logic';

    public function handle()
    {
        $this->info('Simulating CSV Upload...');
        $this->line('==============================');

        $path = public_path('test_sample.csv');
        
        if (!file_exists($path)) {
            $this->error('Test CSV file not found at: ' . $path);
            return;
        }

        // Parse CSV
        $csv = array_map('str_getcsv', file($path));
        
        $headers = array_shift($csv);
        $headers = array_map('strtolower', $headers);
        $headers = array_map('trim', $headers);
        
        $this->info('CSV Headers: ' . implode(', ', $headers));
        $this->info('Total data rows: ' . count($csv));

        // Create import log
        $importLog = ImportLog::create([
            'nama_file' => 'test_sample.csv',
            'wilayah_id' => '16,17,18,19,20',
            'tahun' => 2025,
            'bulan' => 11,
            'minggu' => 1,
            'jumlah_records' => 0,
            'jumlah_berhasil' => 0,
            'jumlah_gagal' => 0,
            'status' => 'pending',
            'user_id' => 1
        ]);

        $this->info('Created ImportLog ID: ' . $importLog->id);

        $berhasil = 0;
        $gagal = 0;
        $errors = [];

        // Flexible field getter
        $getField = function($fieldName) use ($headers) {
            $fieldLower = strtolower($fieldName);
            foreach ($headers as $idx => $key) {
                $keyNorm = str_replace(['_', ' ', '/'], '', strtolower($key));
                $fieldNorm = str_replace(['_', ' ', '/'], '', $fieldLower);
                if ($keyNorm === $fieldNorm) {
                    return function($row) use ($idx) {
                        return $row[$idx] ?? null;
                    };
                }
            }
            return function($row) { return null; };
        };

        $parseFloat2 = function($val) {
            if (empty($val)) return null;
            $val = str_replace(',', '.', trim($val));
            return is_numeric($val) ? (float) $val : null;
        };

        $parseDate2 = function($val) {
            if (empty($val)) return null;
            try {
                $val = trim($val);
                $date = \DateTime::createFromFormat('Y-m-d', $val);
                if ($date === false) $date = \DateTime::createFromFormat('d-m-Y', $val);
                if ($date === false) $date = \DateTime::createFromFormat('d/m/Y', $val);
                if ($date === false) $date = \DateTime::createFromFormat('d-M-Y', $val);
                if ($date === false) $date = \DateTime::createFromFormat('d-M-y', $val);
                if ($date === false) $date = \DateTime::createFromFormat('d M Y', $val);
                if ($date === false) $date = \DateTime::createFromFormat('d M y', $val);
                
                return $date ? $date->format('Y-m-d') : null;
            } catch (\Exception $e) {
                return null;
            }
        };

        // Process rows
        foreach ($csv as $index => $row) {
            if (empty(array_filter($row))) continue;

            try {
                $data = array_combine($headers, $row);
                if ($data === false) {
                    throw new \Exception('Kolom CSV tidak sesuai');
                }
                
                $data = array_map('trim', $data);

                $pgFunc = $getField('pg');
                $fmFunc = $getField('fm');
                $wilFunc = $getField('wil');
                $seksiFunc = $getField('seksi');
                $netoFunc = $getField('neto');
                $hasilFunc = $getField('hasil');
                $umurFunc = $getField('umur_tnm');
                $tnmFunc = $getField('tnm_sts');
                $aktivitasFunc = $getField('activitas');
                $kategoriFunc = $getField('kategori');
                $tanggalFunc = $getField('tanggal');
                $tkhFunc = $getField('tk/ha');
                $totaltkFunc = $getField('total_tk');

                $seksi = $seksiFunc($row);
                if (empty($seksi)) {
                    throw new \Exception('SEKSI kosong');
                }

                $wilayah = $wilFunc($row);
                $wilayahId = !empty($wilayah) ? (int) trim($wilayah) : null;
                
                if (!$wilayahId || $wilayahId < 16 || $wilayahId > 23) {
                    throw new \Exception('Wilayah tidak valid: ' . ($wilayah ?? 'kosong'));
                }

                DataGulma::create([
                    'wilayah_id' => $wilayahId,
                    'id_feature' => $seksi,
                    'import_log_id' => $importLog->id,
                    'pg' => $pgFunc($row),
                    'fm' => $fmFunc($row),
                    'seksi' => $seksi,
                    'neto' => $parseFloat2($netoFunc($row)),
                    'hasil' => $parseFloat2($hasilFunc($row)),
                    'umur' => $parseFloat2($umurFunc($row)),
                    'tnm_sts' => $tnmFunc($row),
                    'activitas' => $aktivitasFunc($row),
                    'kategori' => $kategoriFunc($row),
                    'tanggal' => $parseDate2($tanggalFunc($row)) ?? now()->toDateString(),
                    'tk_ha' => $parseFloat2($tkhFunc($row)),
                    'total_tk' => $parseFloat2($totaltkFunc($row)),
                ]);

                $berhasil++;
                $this->line("  ✓ Row " . ($index + 2) . " inserted");

            } catch (\Exception $e) {
                $gagal++;
                $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                $this->line("  ✗ Row " . ($index + 2) . " failed: " . $e->getMessage());
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

        // Publish
        if ($berhasil > 0) {
            MapPublication::where('tahun', 2025)->where('bulan', 11)->where('minggu', 1)->delete();
            
            MapPublication::create([
                'import_log_id' => $importLog->id,
                'tahun' => 2025,
                'bulan' => 11,
                'minggu' => 1,
                'status' => 'published',
                'published_at' => now(),
                'published_by' => 1
            ]);
            
            $this->info('Published import_log_id: ' . $importLog->id);
        }

        $this->newLine();
        $this->info('==============================');
        $this->info('Results:');
        $this->line("  Berhasil: $berhasil");
        $this->line("  Gagal: $gagal");
        $this->line("  Total: " . ($berhasil + $gagal));
        
        if (!empty($errors)) {
            $this->warn('Errors:');
            foreach ($errors as $err) {
                $this->line("  - $err");
            }
        }

        $this->newLine();
        $this->info('✅ Simulation complete');

        return Command::SUCCESS;
    }
}
