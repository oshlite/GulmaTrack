<?php

namespace App\Http\Controllers;

use App\Models\DataGulma;
use App\Models\ImportLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function dashboard(Request $request)
    {
        // Query data dari database
        $totalDataGulma = DataGulma::count();
        $wilayahAktif = DataGulma::distinct('wilayah_id')->count('wilayah_id');
        $totalTanaman = DataGulma::distinct('id_feature')->count('id_feature');
        
        // Build query for import logs
        $query = ImportLog::latest('created_at');
        
        // Apply search filter if provided
        if ($request->has('search') && $request->search) {
            $search = str_replace('#', '', $request->search); // Strip # prefix to allow searching like "#5"
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('nama_file', 'LIKE', "%{$search}%")
                  ->orWhere('tahun', 'LIKE', "%{$search}%")
                  ->orWhere('bulan', 'LIKE', "%{$search}%")
                  ->orWhere('minggu', 'LIKE', "%{$search}%")
                  ->orWhere('status', 'LIKE', "%{$search}%");
            });
        }
        
        // Apply period filters if provided
        if ($request->has('tahun') && $request->tahun) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->has('bulan') && $request->bulan) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->has('minggu') && $request->minggu) {
            $query->where('minggu', $request->minggu);
        }
        
        // Get all data without pagination - pagination will be handled client-side
        $importTerbaru = $query->get();

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
            'tahun' => 'required|integer|min:1900|digits:4',
            'bulan' => 'required|integer|min:1|max:12',
            'minggu' => 'required|integer|min:1|max:4',
        ], [
            'file.required' => 'File harus dipilih',
            'file.file' => 'File tidak valid',
            'file.mimes' => 'File harus berformat CSV atau TXT',
            'file.max' => 'Ukuran file maksimal 10MB',
            'tahun.required' => 'Tahun harus diisi',
            'tahun.integer' => 'Tahun harus berupa angka',
            'tahun.min' => 'Tahun minimal 1900',
            'tahun.digits' => 'Tahun harus 4 digit',
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
                'tahun' => $request->tahun,
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

            // Auto-publish if upload successful
            if ($berhasil > 0) {
                \App\Models\MapPublication::create([
                    'import_log_id' => $importLog->id,
                    'status' => 'published',
                    'published_at' => now(),
                    'published_by' => auth()->id(),
                    'notes' => 'Auto-published after successful CSV upload'
                ]);
            }

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
            // Get the latest successful import log
            $latestImport = \App\Models\ImportLog::where('status', 'success')
                ->latest('created_at')
                ->first();

            if (!$latestImport) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data import yang berhasil untuk dipublikasikan'
                ], 400);
            }

            // Delete previous publications to ensure only latest is published
            \App\Models\MapPublication::where('status', 'published')->delete();

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
                'published_by' => $latest && $latest->publisher ? $latest->publisher->name : null,
                'import_log_id' => $latest ? $latest->import_log_id : null
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

    /**
     * Get latest published data for map
     */
    public function getLatestPublished()
    {
        try {
            \Log::info('=== getLatestPublished called ===');
            
            // Get latest published map (for admin to see what's being shown to public)
            $published = \App\Models\MapPublication::getLatestPublished();
            
            \Log::info('Published data:', ['published' => $published ? $published->toArray() : null]);
            
            if ($published && $published->importLog) {
                \Log::info('Returning published import log ID: ' . $published->import_log_id);
                
                return response()->json([
                    'success' => true,
                    'import_id' => (int) $published->import_log_id,
                    'tahun' => $published->importLog->tahun ?? null,
                    'bulan' => $published->importLog->bulan ?? null,
                    'minggu' => $published->importLog->minggu ?? null,
                    'published_at' => $published->published_at->format('Y-m-d H:i:s')
                ]);
            }
            
            \Log::info('No published data found, looking for latest successful import');
            
            // If no published data, return the latest successful import instead (fallback)
            $latest = ImportLog::where('status', 'success')
                ->latest('created_at')
                ->first();
            
            \Log::info('Latest import log:', ['latest' => $latest ? $latest->toArray() : null]);
            
            if (!$latest) {
                \Log::warning('No import logs found at all');
                return response()->json([
                    'success' => false,
                    'message' => 'No data found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'import_id' => (int) $latest->id,
                'tahun' => $latest->tahun ?? null,
                'bulan' => $latest->bulan ?? null,
                'minggu' => $latest->minggu ?? null,
                'created_at' => $latest->created_at->format('Y-m-d H:i:s'),
                'note' => 'Using latest upload (no published data)'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getLatestPublished: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Get data gulma by import ID
     */
    public function getDataByImport($importId)
    {
        try {
            $data = DataGulma::where('import_log_id', $importId)->get();
            
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

     /**
     * API: Get summary statistics per wilayah
     * Endpoint: /api/statistik/summary
     */
    public function getStatistikSummary(Request $request)
    {
        try {
            $query = DataGulma::select(
                'wilayah_id',
                DB::raw('COUNT(DISTINCT id_feature) as total_features'),
                DB::raw('SUM(neto) as total_neto'),
                DB::raw('SUM(hasil) as total_hasil'), // Total Gulma
                DB::raw('AVG(hasil) as avg_hasil'),
                DB::raw('AVG(umur_tanaman) as avg_umur'),
                DB::raw('SUM(total_tk) as total_tenaga_kerja')
            )
            ->groupBy('wilayah_id')
            ->orderBy('wilayah_id');

            // Apply filters if provided
            if ($request->has('tahun') && $request->tahun) {
                $query->whereHas('importLog', function($q) use ($request) {
                    $q->where('tahun', $request->tahun);
                });
            }
            if ($request->has('bulan') && $request->bulan) {
                $query->whereHas('importLog', function($q) use ($request) {
                    $q->where('bulan', $request->bulan);
                });
            }
            if ($request->has('minggu') && $request->minggu) {
                $query->whereHas('importLog', function($q) use ($request) {
                    $q->where('minggu', $request->minggu);
                });
            }

            $data = $query->get();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get ranking by total hasil (gulma)
     * Endpoint: /api/statistik/ranking
     */
    public function getStatistikRanking(Request $request)
    {
        try {
            $query = DataGulma::select(
                'wilayah_id',
                DB::raw('SUM(hasil) as total_hasil'),
                DB::raw('AVG(hasil) as avg_hasil'),
                DB::raw('COUNT(DISTINCT id_feature) as jumlah_features')
            )
            ->groupBy('wilayah_id')
            ->orderByDesc('total_hasil')
            ->limit(10);

            // Apply filters
            if ($request->has('tahun') && $request->tahun) {
                $query->whereHas('importLog', function($q) use ($request) {
                    $q->where('tahun', $request->tahun);
                });
            }
            if ($request->has('bulan') && $request->bulan) {
                $query->whereHas('importLog', function($q) use ($request) {
                    $q->where('bulan', $request->bulan);
                });
            }

            $data = $query->get();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get productivity analysis
     * Endpoint: /api/statistik/productivity
     */
    public function getStatistikProductivity(Request $request)
    {
        try {
            // Group features by productivity level based on avg hasil per feature
            $tinggi = DataGulma::select('wilayah_id', 'id_feature')
                ->selectRaw('AVG(hasil) as avg_hasil')
                ->groupBy('wilayah_id', 'id_feature')
                ->havingRaw('AVG(hasil) > 9')
                ->get();

            $sedang = DataGulma::select('wilayah_id', 'id_feature')
                ->selectRaw('AVG(hasil) as avg_hasil')
                ->groupBy('wilayah_id', 'id_feature')
                ->havingRaw('AVG(hasil) >= 8 AND AVG(hasil) <= 9')
                ->get();

            $rendah = DataGulma::select('wilayah_id', 'id_feature')
                ->selectRaw('AVG(hasil) as avg_hasil')
                ->groupBy('wilayah_id', 'id_feature')
                ->havingRaw('AVG(hasil) < 8')
                ->get();

            $data = [
                'tinggi' => [
                    'count' => $tinggi->count(),
                    'avg' => $tinggi->avg('avg_hasil') ? round($tinggi->avg('avg_hasil'), 2) : 0
                ],
                'sedang' => [
                    'count' => $sedang->count(),
                    'avg' => $sedang->avg('avg_hasil') ? round($sedang->avg('avg_hasil'), 2) : 0
                ],
                'rendah' => [
                    'count' => $rendah->count(),
                    'avg' => $rendah->avg('avg_hasil') ? round($rendah->avg('avg_hasil'), 2) : 0
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get yearly comparison
     * Endpoint: /api/statistik/yearly-comparison
     */
    public function getStatistikYearlyComparison(Request $request)
    {
        try {
            $data = ImportLog::select('tahun')
                ->selectRaw('SUM((SELECT SUM(hasil) FROM data_gulma WHERE import_log_id = import_logs.id)) as total_hasil')
                ->where('status', 'success')
                ->whereNotNull('tahun')
                ->groupBy('tahun')
                ->orderBy('tahun')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get wilayah detail for statistik
     * Endpoint: /api/statistik/wilayah/{wilayah_id}
     */
    public function getStatistikWilayahDetail($wilayahId, Request $request)
    {
        try {
            $query = DataGulma::where('wilayah_id', $wilayahId);

            // Apply period filter if provided
            if ($request->has('tahun') && $request->tahun) {
                $query->whereHas('importLog', function($q) use ($request) {
                    $q->where('tahun', $request->tahun);
                });
            }
            if ($request->has('bulan') && $request->bulan) {
                $query->whereHas('importLog', function($q) use ($request) {
                    $q->where('bulan', $request->bulan);
                });
            }
            if ($request->has('minggu') && $request->minggu) {
                $query->whereHas('importLog', function($q) use ($request) {
                    $q->where('minggu', $request->minggu);
                });
            }

            $summary = [
                'wilayah_id' => $wilayahId,
                'total_features' => $query->distinct('id_feature')->count('id_feature'),
                'total_neto' => $query->sum('neto'),
                'total_hasil' => $query->sum('hasil'),
                'avg_hasil' => $query->avg('hasil'),
                'avg_umur' => $query->avg('umur_tanaman'),
                'total_tk' => $query->sum('total_tk'),
                'kategori_distribution' => DataGulma::where('wilayah_id', $wilayahId)
                    ->select('kategori', DB::raw('COUNT(*) as count'))
                    ->groupBy('kategori')
                    ->get()
            ];

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get comparison data (for comparison feature)
     * Endpoint: /api/statistik/comparison
     */
    public function getStatistikComparison(Request $request)
    {
        try {
            // Get total production across all wilayah
            $totalProduction = DataGulma::sum('hasil');
            
            // Get data per wilayah with percentage
            $data = DataGulma::select(
                'wilayah_id',
                DB::raw('SUM(hasil) as total_hasil'),
                DB::raw('SUM(neto) as total_neto'),
                DB::raw('AVG(hasil) as avg_hasil'),
                DB::raw('COUNT(DISTINCT id_feature) as total_features')
            )
            ->groupBy('wilayah_id')
            ->orderBy('wilayah_id')
            ->get()
            ->map(function($item) use ($totalProduction) {
                $item->percentage = $totalProduction > 0 
                    ? round(($item->total_hasil / $totalProduction) * 100, 2) 
                    : 0;
                return $item;
            });

            return response()->json([
                'success' => true,
                'total_production' => $totalProduction,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getByPeriod(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'bulan' => 'required|integer',
        ]);

        $publication = \App\Models\MapPublication::where('tahun', $request->tahun)
            ->where('bulan', $request->bulan)
            ->where('status', 'published')
            ->orderByDesc('minggu')
            ->first();

        if (!$publication) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $publication
        ]);
    }

}
