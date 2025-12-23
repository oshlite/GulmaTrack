<?php

namespace App\Http\Controllers;

use App\Models\DataGulma;
use App\Models\ImportLog;
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
        $importTerbaru = ImportLog::latest('created_at')->limit(5)->get();

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
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ], [
            'file.required' => 'File harus dipilih',
            'file.file' => 'File tidak valid',
            'file.mimes' => 'File harus berformat CSV atau TXT',
            'file.max' => 'Ukuran file maksimal 10MB',
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $csv = array_map('str_getcsv', file($path));
            
            // Validate headers
            $headers = array_shift($csv);
            $headers = array_map('strtolower', $headers);
            $headers = array_map('trim', $headers);

            // Check if wilayah column exists
            $wilayahIndex = array_search('wilayah', $headers);
            if ($wilayahIndex === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kolom "wilayah" tidak ditemukan di CSV'
                ], 400);
            }

            // Get wilayah dari baris pertama
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

            $required = ['id_feature', 'status_gulma', 'persentase', 'tanggal'];
            $missing = array_diff($required, $headers);

            if (!empty($missing)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kolom CSV tidak lengkap: ' . implode(', ', $missing)
                ], 400);
            }

            // Create import log
            $importLog = ImportLog::create([
                'nama_file' => $file->getClientOriginalName(),
                'wilayah_id' => $wilayahId,
                'jumlah_records' => 0,
                'jumlah_berhasil' => 0,
                'jumlah_gagal' => 0,
                'status' => 'pending',
                'user_id' => auth()->id()
            ]);

            $berhasil = 0;
            $gagal = 0;
            $errors = [];

            // Process each row
            foreach ($csv as $index => $row) {
                if (empty(array_filter($row))) continue;

                try {
                    $data = array_combine($headers, $row);
                    $data = array_map('trim', $data);

                    // Validation
                    if (empty($data['id_feature'])) {
                        throw new \Exception('ID Feature kosong');
                    }

                    if (!in_array($data['status_gulma'], ['Bersih', 'Ringan', 'Sedang', 'Berat'])) {
                        throw new \Exception('Status gulma tidak valid: ' . $data['status_gulma']);
                    }

                    $persentase = (int) $data['persentase'];
                    if ($persentase < 0 || $persentase > 100) {
                        throw new \Exception('Persentase harus antara 0-100');
                    }

                    // Save to database
                    DataGulma::updateOrCreate(
                        [
                            'wilayah_id' => $wilayahId,
                            'id_feature' => $data['id_feature'],
                        ],
                        [
                            'status_gulma' => $data['status_gulma'],
                            'persentase' => $persentase,
                            'tanggal' => $data['tanggal'],
                            'import_log_id' => $importLog->id
                        ]
                    );

                    $berhasil++;
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

            $message = "File CSV berhasil diproses! Berhasil: $berhasil, Gagal: $gagal";
            
            // Return JSON untuk AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'wilayah_id' => $wilayahId,
                    'berhasil' => $berhasil,
                    'gagal' => $gagal
                ]);
            }

            return redirect()->route('admin.dashboard')
                ->with('success', $message);

        } catch (\Exception $e) {
            $message = 'Error: ' . $e->getMessage();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }

            return redirect()->route('admin.dashboard')
                ->with('error', $message);
        }
    }
}