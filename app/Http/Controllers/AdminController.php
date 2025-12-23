<?php

namespace App\Http\Controllers;

use App\Models\DataGulma;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Tampilkan dashboard admin dengan data dari database
     */
    public function dashboard()
    {
        // Query data dari database
        $totalDataGulma = DataGulma::count();
        $wilayahAktif = DataGulma::distinct('wilayah_id')->count('wilayah_id');
        $totalTanaman = DataGulma::distinct('id_feature')->count('id_feature');
        $importTerbaru = collect([]); // Empty collection

        return view('admin.dashboard', [
            'totalDataGulma' => $totalDataGulma,
            'wilayahAktif' => $wilayahAktif,
            'totalTanaman' => $totalTanaman,
            'importTerbaru' => $importTerbaru,
        ]);
    }

    /**
     * Handle CSV upload dengan validasi dan parsing
     * Auto detect wilayah dari CSV file
     */
    public function uploadCsv(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:csv,txt|max:10240',
            ], [
                'file.required' => 'File harus dipilih',
                'file.file' => 'File tidak valid',
                'file.mimes' => 'File harus berformat CSV atau TXT',
                'file.max' => 'Ukuran file maksimal 10MB',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            
            // Read CSV with UTF-8 encoding
            $content = file_get_contents($path);
            $content = mb_convert_encoding($content, 'UTF-8', 'auto');
            $lines = explode("\n", $content);
            $csv = array_map('str_getcsv', $lines);
            
            // Remove empty lines
            $csv = array_filter($csv, function($row) {
                return !empty(array_filter($row));
            });
            $csv = array_values($csv); // Re-index
            
            // Validate headers
            $headers = array_shift($csv);
            $originalHeaders = $headers; // Keep original case
            $headers = array_map('strtolower', $headers);
            $headers = array_map('trim', $headers);

            // Check if wilayah column exists
            $wilayahIndex = array_search('wilayah', $headers);
            if ($wilayahIndex === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kolom "Wilayah" tidak ditemukan di CSV'
                ], 400);
            }

            // Required columns for new format (minimal yang diperlukan)
            $requiredColumns = ['wilayah', 'seksi', 'kategori'];
            $missing = [];
            
            foreach ($requiredColumns as $col) {
                if (!in_array($col, $headers)) {
                    $missing[] = $col;
                }
            }

            if (!empty($missing)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kolom CSV tidak lengkap: ' . implode(', ', $missing) . '. Kolom yang ditemukan: ' . implode(', ', $headers)
                ], 400);
            }

            // Get wilayah dari baris pertama untuk validasi
            $wilayahId = null;
            if (!empty($csv[0][$wilayahIndex])) {
                $wilayahId = (int) trim($csv[0][$wilayahIndex]);
            }

            if (!$wilayahId || $wilayahId < 16 || $wilayahId > 23) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wilayah tidak valid. Harus antara 16-23'
                ], 400);
            }

            $berhasil = 0;
            $gagal = 0;
            $errors = [];

            // Process each row
            foreach ($csv as $index => $row) {
                if (empty(array_filter($row))) continue;

                try {
                    $data = array_combine($headers, $row);
                    $data = array_map('trim', $data);

                    // Get wilayah from row
                    $currentWilayah = (int) $data['wilayah'];
                    
                    // Validation
                    if (empty($data['seksi'])) {
                        throw new \Exception('SEKSI kosong');
                    }

                    // Use SEKSI as id_feature
                    $idFeature = $data['seksi'];

                    // Determine status_gulma based on KATEGORI
                    $kategori = strtolower(trim($data['kategori']));
                    $statusGulma = 'Bersih'; // default
                    $persentase = 0; // default

                    if (strpos($kategori, 'berat') !== false) {
                        $statusGulma = 'Berat';
                        $persentase = 75;
                    } elseif (strpos($kategori, 'sedang') !== false) {
                        $statusGulma = 'Sedang';
                        $persentase = 50;
                    } elseif (strpos($kategori, 'ringan') !== false) {
                        $statusGulma = 'Ringan';
                        $persentase = 25;
                    }

                    // Use current date if tanggal not provided
                    $tanggal = date('Y-m-d');

                    // Save to database
                    DataGulma::updateOrCreate(
                        [
                            'wilayah_id' => $currentWilayah,
                            'id_feature' => $idFeature,
                        ],
                        [
                            'status_gulma' => $statusGulma,
                            'persentase' => $persentase,
                            'tanggal' => $tanggal
                        ]
                    );

                    $berhasil++;
                } catch (\Exception $e) {
                    $gagal++;
                    $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            $message = "File CSV berhasil diproses! Berhasil: $berhasil, Gagal: $gagal";
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'wilayah_id' => $wilayahId,
                'berhasil' => $berhasil,
                'gagal' => $gagal
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}