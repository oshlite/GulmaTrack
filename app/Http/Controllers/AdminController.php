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
            'bulan' => 'required|integer|min:1|max:12',
            'minggu' => 'required|integer|min:1|max:4',
        ], [
            'file.required' => 'File harus dipilih',
            'file.file' => 'File tidak valid',
            'file.mimes' => 'File harus berformat CSV atau TXT',
            'file.max' => 'Ukuran file maksimal 10MB',
            'bulan.required' => 'Bulan harus dipilih',
            'bulan.min' => 'Bulan harus antara 1-12',
            'bulan.max' => 'Bulan harus antara 1-12',
            'minggu.required' => 'Minggu harus dipilih',
            'minggu.min' => 'Minggu harus antara 1-4',
            'minggu.max' => 'Minggu harus antara 1-4',
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $csv = array_map('str_getcsv', file($path));
            
            // Validate headers
            $headers = array_shift($csv);
            $headers = array_map('strtolower', $headers);
            $headers = array_map('trim', $headers);

            // Check required columns: PG, FM, Wilayah, SEKSI, dll
            $required = ['pg', 'fm', 'wilayah', 'seksi'];
            $missing = array_diff($required, $headers);

            if (!empty($missing)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kolom CSV tidak lengkap. Kolom wajib: PG, FM, Wilayah, SEKSI, Neto, Hasil, Umur Tanaman, Penanggungjawab, Kode Aktf, ACTIVITAS, KATEGORI, TK/HA, TOTAL TK'
                ], 400);
            }

            // Collect all unique wilayah from CSV
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

            if (empty($allWilayah)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada wilayah valid (16-23) dalam CSV'
                ], 400);
            }

            // Create import log with all wilayah (comma separated)
            $wilayahList = implode(',', array_keys($allWilayah));
            $importLog = ImportLog::create([
                'nama_file' => $file->getClientOriginalName(),
                'wilayah_id' => $wilayahList,
                'tahun' => $request->tahun ?? now()->year,
                'bulan' => $request->bulan,
                'minggu' => $request->minggu,
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

                    // Validation basic
                    if (empty($data['seksi'])) {
                        throw new \Exception('SEKSI kosong');
                    }

                    // Get wilayah from current row
                    $rowWilayahId = !empty($data['wilayah']) ? (int) $data['wilayah'] : null;
                    
                    if (!$rowWilayahId || $rowWilayahId < 16 || $rowWilayahId > 23) {
                        throw new \Exception('Wilayah tidak valid: ' . ($data['wilayah'] ?? 'kosong'));
                    }

                    // id_feature langsung dari SEKSI (ini harus match dengan property di GeoJSON)
                    $idFeature = $data['seksi'];

                    // Parse numeric values dengan handling empty/null
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

            $wilayahText = count($allWilayah) > 1 ? 'Wilayah ' . $wilayahList : 'Wilayah ' . $wilayahList;
            $message = "File CSV berhasil diproses! $wilayahText - Berhasil: $berhasil, Gagal: $gagal";
            
            // Return JSON untuk AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'wilayah_id' => $wilayahList,
                    'wilayah_count' => count($allWilayah),
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

    /**
     * Get kategori color mapping dari data yang ada di database
     */
    public function getKategoriColors()
    {
        try {
            // Get unique kategori dari database
            $kategoris = DataGulma::whereNotNull('kategori')
                ->distinct()
                ->pluck('kategori')
                ->filter()
                ->values();

            // Generate warna untuk setiap kategori
            $colorMap = [];
            $colors = [
                '#22c55e', // green-500 - Bersih
                '#84cc16', // lime-500
                '#eab308', // yellow-500 - Ringan  
                '#f97316', // orange-500 - Sedang
                '#ef4444', // red-500 - Berat
                '#dc2626', // red-600
                '#991b1b', // red-800
                '#6b7280', // gray-500
            ];

            foreach ($kategoris as $index => $kategori) {
                $colorIndex = $index % count($colors);
                $colorMap[$kategori] = $colors[$colorIndex];
            }

            return response()->json([
                'success' => true,
                'data' => $colorMap
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Publish map data to public view
     */
    public function publishMap(Request $request)
    {
        try {
            // Create new publication record
            $publication = \App\Models\MapPublication::create([
                'status' => 'published',
                'published_at' => now(),
                'published_by' => auth()->id(),
                'notes' => $request->notes ?? 'Publikasi peta dengan data terbaru'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Peta berhasil dipublikasikan! Data sekarang dapat dilihat oleh publik.',
                'published_at' => $publication->published_at->format('d M Y H:i')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get publication status
     */
    public function getPublicationStatus()
    {
        try {
            $latest = \App\Models\MapPublication::getLatestPublished();
            
            return response()->json([
                'success' => true,
                'is_published' => $latest !== null,
                'published_at' => $latest ? $latest->published_at->format('d M Y H:i') : null,
                'published_by' => $latest && $latest->publisher ? $latest->publisher->name : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard statistics
     */
    public function getStatistics()
    {
        try {
            $totalDataGulma = DataGulma::count();
            $wilayahAktif = DataGulma::distinct('wilayah_id')->count('wilayah_id');
            $totalTanaman = DataGulma::distinct('id_feature')->count('id_feature');
            $uploadTerbaru = ImportLog::count();

            return response()->json([
                'success' => true,
                'data' => [
                    'totalDataGulma' => $totalDataGulma,
                    'wilayahAktif' => $wilayahAktif,
                    'totalTanaman' => $totalTanaman,
                    'uploadTerbaru' => $uploadTerbaru
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}