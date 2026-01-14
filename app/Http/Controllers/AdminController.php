<?php
namespace App\Http\Controllers;

use App\Models\DataGulma;
use App\Models\ImportLog;
use App\Models\Drone;
use App\Models\MapPublication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;  // ✅ Ini harus ada
use Illuminate\Support\Facades\Cache; // ✅ Tambah ini juga

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
     * Tampilkan dashboard admin dengan data dari published import
     * Atau dari temp import jika admin baru saja upload file CSV
     */
    public function dashboard(Request $request)
    {
        // Cek apakah ada temp import_log_id dari upload baru (session)
        $tempImportLogId = session('temp_import_log_id');
        
        if ($tempImportLogId) {
            // Tampilkan statistik dari upload baru yang belum dipublish
            $totalDataGulma = DataGulma::where('import_log_id', $tempImportLogId)->count();
            $wilayahAktif = DataGulma::where('import_log_id', $tempImportLogId)->distinct('wilayah_id')->count('wilayah_id');
            $totalTanaman = DataGulma::where('import_log_id', $tempImportLogId)->distinct('id_feature')->count('id_feature');
        } else {
            // DEFAULT: Tampilkan statistik dari published data saja
            $published = MapPublication::getLatestPublished();
            
            if ($published && $published->importLog) {
                // Ada publikasi, tampilkan dari situ
                $totalDataGulma = DataGulma::where('import_log_id', $published->import_log_id)->count();
                $wilayahAktif = DataGulma::where('import_log_id', $published->import_log_id)->distinct('wilayah_id')->count('wilayah_id');
                $totalTanaman = DataGulma::where('import_log_id', $published->import_log_id)->distinct('id_feature')->count('id_feature');
            } else {
                // Tidak ada publikasi, ambil latest successful import saja
                $latest = ImportLog::where('status', 'success')->latest('created_at')->first();
                
                if ($latest) {
                    $totalDataGulma = DataGulma::where('import_log_id', $latest->id)->count();
                    $wilayahAktif = DataGulma::where('import_log_id', $latest->id)->distinct('wilayah_id')->count('wilayah_id');
                    $totalTanaman = DataGulma::where('import_log_id', $latest->id)->distinct('id_feature')->count('id_feature');
                } else {
                    // Benar-benar tidak ada data
                    $totalDataGulma = 0;
                    $wilayahAktif = 0;
                    $totalTanaman = 0;
                }
            }
        }
        
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

        // Drone Statistics
        $totalDrone = Drone::count();
        $droneAktif = Drone::whereYear('tanggal_perencanaan', date('Y'))->count();
        $totalPdf = Drone::count();
        $droneUploadTerbaru = Drone::orderBy('created_at', 'desc')->first();

        return view('admin.dashboard', [
            'totalDataGulma' => $totalDataGulma,
            'wilayahAktif' => $wilayahAktif,
            'totalTanaman' => $totalTanaman,
            'importTerbaru' => $importTerbaru,
            'totalDrone' => $totalDrone,
            'droneAktif' => $droneAktif,
            'totalPdf' => $totalPdf,
            'droneUploadTerbaru' => $droneUploadTerbaru,
        ]);
    }

    /**
     * Handle CSV upload dengan validasi dan parsing
     * Auto detect wilayah dari CSV file
     */
    public function uploadCsv(Request $request)
    {
        Log::info('Upload CSV request received', [
            'has_file' => $request->hasFile('file'),
            'tahun' => $request->input('tahun'),
            'bulan' => $request->input('bulan'),
            'minggu' => $request->input('minggu'),
        ]);
        
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
            'tahun' => 'required|integer|min:1900|digits:4',
            'bulan' => 'required|integer|min:1|max:12',
            'minggu' => 'required|integer|min:1|max:4',
        ], [
            'file.required' => 'File harus dipilih',
            'file.mimes' => 'File harus berformat CSV atau TXT',
            'tahun.required' => 'Tahun harus diisi',
            'bulan.required' => 'Bulan harus dipilih',
            'minggu.required' => 'Minggu harus dipilih',
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            
            Log::info('Starting CSV upload', [
                'file' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
            ]);
            
            // Read file content
            $fileContent = file_get_contents($path);
            
            // Detect delimiter - check if first line has tabs or commas
            $firstLine = strtok($fileContent, "\n");
            $delimiter = (strpos($firstLine, "\t") !== false) ? "\t" : ",";
            
            Log::info('Detected delimiter', [
                'delimiter' => $delimiter === "\t" ? 'TAB' : 'COMMA',
                'first_line_sample' => substr($firstLine, 0, 100)
            ]);
            
            // Parse CSV with detected delimiter
            $csv = [];
            $lines = explode("\n", $fileContent);
            foreach ($lines as $line) {
                if (!empty(trim($line))) {
                    $csv[] = str_getcsv($line, $delimiter);
                }
            }
            
            // Validate headers - normalize to match expected format
            $headers = array_shift($csv);
            $headers = array_map('strtolower', $headers);
            $headers = array_map('trim', $headers);
            
            // Log raw headers untuk debugging
            Log::warning('CSV UPLOAD DEBUG - RAW HEADERS', [
                'count' => count($headers),
                'headers' => $headers,
                'headers_json' => json_encode($headers),
                'first_header_bytes' => bin2hex($headers[0] ?? ''),
                'filename' => $file->getClientOriginalName()
            ]);
            
            // Normalize header names untuk flexible matching
            $headerMap = [];
            foreach ($headers as $idx => $header) {
                // Remove BOM if exists
                $header = str_replace("\xEF\xBB\xBF", '', $header);
                // Normalize header variations
                $normalized = str_replace(['_', ' ', '/', '.', '-'], '', $header);
                $headerMap[$idx] = $normalized;
            }
            
            Log::warning('CSV UPLOAD DEBUG - NORMALIZED HEADERS', [
                'headerMap' => $headerMap
            ]);
            
            $required = ['pg', 'fm', 'wil', 'seksi'];
            Log::info('Required headers:', $required);
            Log::info('Normalized headers:', $headerMap);
            
            // Validate required headers exist
            $hasAllRequired = true;
            foreach ($required as $req) {
                $found = false;
                foreach ($headerMap as $normalized) {
                    if (strpos($normalized, $req) !== false) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $hasAllRequired = false;
                    break;
                }
            }
            
            if (!$hasAllRequired) {
                Log::warning('Missing required columns. Headers: ' . json_encode($headers));
                return response()->json([
                    'success' => false,
                    'message' => 'Kolom CSV tidak lengkap. Kolom wajib: PG, FM, WIL, SEKSI'
                ], 400);
            }

            // Collect all unique wilayah from CSV - find WIL column
            $wilayahIndex = null;
            foreach ($headers as $idx => $header) {
                $normalized = str_replace(['_', ' ', '/'], '', strtolower($header));
                if ($normalized === 'wil') {
                    $wilayahIndex = $idx;
                    break;
                }
            }
            
            if ($wilayahIndex === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kolom WIL tidak ditemukan dalam CSV'
                ], 400);
            }
            
            $allWilayah = [];
            foreach ($csv as $row) {
                if (isset($row[$wilayahIndex]) && !empty($row[$wilayahIndex])) {
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

            // Create import log
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

            // Delete ONLY existing records for THIS IMPORT
            DataGulma::where('import_log_id', $importLog->id)->delete();
            
            Log::info("Deleted previous records for import {$importLog->id}");

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
                    if ($data === false) {
                        Log::warning("Row {$index}: array_combine failed", [
                            'headers_count' => count($headers),
                            'row_count' => count($row),
                            'row' => $row
                        ]);
                        throw new \Exception('Kolom CSV tidak sesuai dengan header');
                    }
                    
                    $data = array_map('trim', $data);
                    
                    // Create flexible getter function
                    $getField = function($fieldName) use ($data, $headers) {
                        $fieldLower = strtolower($fieldName);
                        foreach ($data as $key => $val) {
                            $keyNorm = str_replace(['_', ' ', '/', '.', '-'], '', strtolower($key));
                            $fieldNorm = str_replace(['_', ' ', '/', '.', '-'], '', $fieldLower);
                            if ($keyNorm === $fieldNorm) {
                                return $val;
                            }
                        }
                        return null;
                    };

                    $seksi = $getField('seksi');
                    if (empty($seksi)) {
                        throw new \Exception('SEKSI kosong');
                    }

                    $wilayah = $getField('wil');
                    $rowWilayahId = !empty($wilayah) ? (int) trim($wilayah) : null;
                    
                    if (!$rowWilayahId || $rowWilayahId < 16 || $rowWilayahId > 23) {
                        throw new \Exception('Wilayah tidak valid: ' . ($wilayah ?? 'kosong'));
                    }

                    $parseFloat2 = function($val) {
                        if (empty($val)) return null;
                        $val = str_replace(',', '.', trim($val));
                        return is_numeric($val) ? (float) $val : null;
                    };
                    
                    $parseInt2 = function($val) {
                        if (empty($val)) return null;
                        return is_numeric($val) ? (int) trim($val) : null;
                    };

                    $parseDate2 = function($val) {
                        if (empty($val)) return null;
                        try {
                            $val = trim($val);
                            // Try multiple date formats
                            $date = \DateTime::createFromFormat('Y-m-d', $val);
                            if ($date === false) $date = \DateTime::createFromFormat('d-m-Y', $val);
                            if ($date === false) $date = \DateTime::createFromFormat('d/m/Y', $val);
                            if ($date === false) $date = \DateTime::createFromFormat('d-M-Y', $val); // 2-Nov-2025
                            if ($date === false) $date = \DateTime::createFromFormat('d-M-y', $val); // 2-Nov-25
                            if ($date === false) $date = \DateTime::createFromFormat('d M Y', $val);
                            if ($date === false) $date = \DateTime::createFromFormat('d M y', $val);
                            
                            return $date ? $date->format('Y-m-d') : null;
                        } catch (\Exception $e) {
                            return null;
                        }
                    };

                    DataGulma::updateOrCreate(
                        [
                            'wilayah_id' => $rowWilayahId,
                            'id_feature' => $seksi,
                            'import_log_id' => $importLog->id
                        ],
                        [
                            'pg' => $getField('pg'),
                            'fm' => $getField('fm'),
                            'seksi' => $seksi,
                            'neto' => $parseFloat2($getField('neto')),
                            'hasil' => $parseFloat2($getField('hasil')),
                            'umur' => $parseFloat2($getField('umur_tnm') ?? $getField('umur tanaman') ?? $getField('umur')),
                            'tnm_sts' => $getField('tnm_sts') ?? $getField('tnm sts'),
                            'activitas' => $getField('activitas') ?? $getField('aktivitas'),
                            'kategori' => $getField('kategori'),
                            'tanggal' => $parseDate2($getField('tanggal')) ?? now()->toDateString(),
                            'tk_ha' => $parseFloat2($getField('tk/ha') ?? $getField('tkha')),
                            'total_tk' => $parseFloat2($getField('total_tk') ?? $getField('total tk')),
                        ]
                    );

                    $berhasil++;
                    
                    // Log first few successful rows
                    if ($berhasil <= 3) {
                        Log::info("Row {$index} SUCCESS - Wilayah: {$rowWilayahId}, Seksi: {$seksi}");
                    }
                    
                } catch (\Exception $e) {
                    $gagal++;
                    $errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                    
                    // Log detailed error for first few failures
                    if ($gagal <= 5) {
                        Log::error("Row {$index} FAILED", [
                            'error' => $e->getMessage(),
                            'row_data' => $row,
                            'row_count' => count($row),
                            'headers_count' => count($headers)
                        ]);
                    }
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

            // ✅ FIX 1: AUTO-PUBLISH UPLOAD TERBARU
            Log::info('🚀 AUTO-PUBLISHING latest upload...');
            
            // Unpublish semua publikasi lama
            MapPublication::where('status', 'published')
                ->update(['status' => 'draft']);
            
            // Publish upload yang baru saja berhasil
            if ($berhasil > 0) {
                $publication = MapPublication::updateOrCreate(
                    [
                        'tahun' => $importLog->tahun,
                        'bulan' => $importLog->bulan,
                        'minggu' => $importLog->minggu
                    ],
                    [
                        'import_log_id' => $importLog->id,
                        'status' => 'published',
                        'published_at' => now(),
                        'published_by' => auth()->id()
                    ]
                );
                
                Log::info('✅ Auto-published import_log_id: ' . $importLog->id);
            }

            // ✅ FIX 2: CLEAR ALL CACHE (Critical!)
            Log::info("🗑️ Clearing ALL GeoJSON cache after upload...");
            for ($wil = 16; $wil <= 23; $wil++) {
                $cacheKey = "geojson_wgs84_wil_{$wil}";
                Cache::forget($cacheKey);
                Log::info("   ✓ Cleared cache: {$cacheKey}");
            }
            Cache::forget('wilayah_summary_all');
            
            // ✅ FIX 3: NO MORE temp_import_log_id session
            // Dashboard dan Wilayah akan langsung baca dari published data
            
            $wilayahText = count($allWilayah) > 1 ? 'Wilayah ' . $wilayahList : 'Wilayah ' . $wilayahList;
            $message = "✅ File CSV berhasil diproses dan dipublikasikan! $wilayahText - Berhasil: $berhasil, Gagal: $gagal";
            
            Log::info('✅ CSV upload & auto-publish complete', [
                'import_id' => $importLog->id,
                'berhasil' => $berhasil,
                'gagal' => $gagal,
                'wilayah' => $wilayahList
            ]);
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'wilayah_id' => $wilayahList,
                'wilayah_count' => count($allWilayah),
                'berhasil' => $berhasil,
                'gagal' => $gagal,
                'import_log_id' => $importLog->id
            ], 200);

        } catch (\Exception $e) {
            $message = 'Error: ' . $e->getMessage();
            
            Log::error('CSV upload error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $message
            ], 400);
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
     * Menggunakan data dari temp session jika ada, atau latest successful import
     */
    public function publishMap(Request $request)
    {
        try {
            $importLogId = $request->input('import_log_id');
            
            if (!$importLogId) {
                // Get latest successful import
                $latest = ImportLog::where('status', 'success')
                    ->latest('created_at')
                    ->first();
                
                if (!$latest) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak ada data import. Upload CSV terlebih dahulu.'
                    ], 400);
                }
                
                $importLogId = $latest->id;
            }

            $importToDeploy = ImportLog::find($importLogId);
            
            if (!$importToDeploy || $importToDeploy->status !== 'success') {
                return response()->json([
                    'success' => false,
                    'message' => 'File import tidak valid atau gagal'
                ], 400);
            }

            Log::info("📤 Publishing import_log_id: {$importLogId}");

            // Unpublish old publications
            MapPublication::where('status', 'published')
                ->update(['status' => 'draft']);

            // Create new publication
            $publication = MapPublication::updateOrCreate(
                [
                    'tahun' => $importToDeploy->tahun,
                    'bulan' => $importToDeploy->bulan,
                    'minggu' => $importToDeploy->minggu
                ],
                [
                    'import_log_id' => $importToDeploy->id,
                    'status' => 'published',
                    'published_at' => now(),
                    'published_by' => auth()->id()
                ]
            );

            // ✅ CRITICAL: Clear ALL GeoJSON cache
            Log::info("🗑️ Clearing ALL GeoJSON cache after publish...");
            for ($wil = 16; $wil <= 23; $wil++) {
                $cacheKey = "geojson_wgs84_wil_{$wil}";
                Cache::forget($cacheKey);
                Log::info("   ✓ Cleared: {$cacheKey}");
            }
            Cache::forget('wilayah_summary_all');

            return response()->json([
                'success' => true,
                'message' => '✅ Peta berhasil dipublikasikan!',
                'published_at' => $publication->published_at->format('d M Y H:i'),
                'import_id' => $importToDeploy->id,
                'nama_file' => $importToDeploy->nama_file,
                'tahun' => $importToDeploy->tahun,
                'bulan' => $importToDeploy->bulan,
                'minggu' => $importToDeploy->minggu
            ]);
        } catch (\Exception $e) {
            Log::error('Publish map error: ' . $e->getMessage());
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
            $latest = MapPublication::getLatestPublished();
            
            if ($latest && $latest->importLog) {
                return response()->json([
                    'success' => true,
                    'is_published' => true,
                    'published_at' => $latest->published_at->format('d M Y H:i'),
                    'published_by' => $latest->publisher ? $latest->publisher->name : null,
                    'import_id' => $latest->import_log_id,
                    'import_log' => [
                        'id' => $latest->importLog->id,
                        'tahun' => $latest->importLog->tahun,
                        'bulan' => $latest->importLog->bulan,
                        'minggu' => $latest->importLog->minggu,
                        'wilayah_id' => $latest->importLog->wilayah_id,
                        'nama_file' => $latest->importLog->nama_file
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'is_published' => false,
                    'published_at' => null,
                    'published_by' => null,
                    'import_id' => null
                ]);
            }
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
     * API: Get import logs list with optional filters
     * Endpoint: /api/import-logs?tahun=2024&bulan=1&minggu=1
     */
    public function getImportLogs(Request $request)
    {
        try {
            $query = ImportLog::latest('created_at');

            // Apply filters if provided
            if ($request->has('tahun') && $request->tahun) {
                $query->where('tahun', $request->tahun);
            }
            if ($request->has('bulan') && $request->bulan) {
                $query->where('bulan', $request->bulan);
            }
            if ($request->has('minggu') && $request->minggu) {
                $query->where('minggu', $request->minggu);
            }

            // Get data
            $logs = $query->get();

            return response()->json([
                'success' => true,
                'data' => $logs->map(function($log) {
                    return [
                        'id' => $log->id,
                        'nama_file' => $log->nama_file,
                        'tahun' => $log->tahun,
                        'bulan' => $log->bulan,
                        'minggu' => $log->minggu,
                        'status' => $log->status,
                        'wilayah_id' => $log->wilayah_id,
                        'created_at' => $log->created_at->format('Y-m-d H:i:s')
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getImportLogs: ' . $e->getMessage());
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
            Log::info('=== getLatestPublished called ===');
            
            // Get latest published map (for admin to see what's being shown to public)
            $published = MapPublication::getLatestPublished();
            
            Log::info('Published data:', ['published' => $published ? $published->toArray() : null]);
            
            if ($published && $published->importLog) {
                Log::info('Returning published import log ID: ' . $published->import_log_id);
                
                return response()->json([
                    'success' => true,
                    'import_id' => (int) $published->import_log_id,
                    'tahun' => $published->importLog->tahun ?? null,
                    'bulan' => $published->importLog->bulan ?? null,
                    'minggu' => $published->importLog->minggu ?? null,
                    'published_at' => $published->published_at->format('Y-m-d H:i:s')
                ]);
            }
            
            Log::info('No published data found, looking for latest successful import');
            
            // If no published data, return the latest successful import instead (fallback)
            $latest = ImportLog::where('status', 'success')
                ->latest('created_at')
                ->first();
            
            Log::info('Latest import log:', ['latest' => $latest ? $latest->toArray() : null]);
            
            if (!$latest) {
                Log::warning('No import logs found at all');
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
            Log::error('Error in getLatestPublished: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
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
            Log::info("Getting data for import ID: $importId");
            
            // Get import log first to verify it exists
            $importLog = ImportLog::find($importId);
            if (!$importLog) {
                Log::warning("Import log not found: $importId");
                return response()->json([
                    'success' => false,
                    'message' => 'Import not found',
                    'data' => []
                ], 404);
            }
            
            Log::info("Import log found", [
                'id' => $importLog->id,
                'nama_file' => $importLog->nama_file,
                'tahun' => $importLog->tahun,
                'bulan' => $importLog->bulan,
                'minggu' => $importLog->minggu,
                'status' => $importLog->status,
                'wilayah_id' => $importLog->wilayah_id
            ]);
            
            // Get all data for this import
            $data = DataGulma::where('import_log_id', $importId)->get();
            
            Log::info("Database query result", [
                'import_log_id' => $importId,
                'count' => $data->count(),
                'first_record' => $data->first() ? $data->first()->toArray() : null
            ]);
            
            // If no data found, check if there's any published data to show as fallback
            if ($data->count() === 0) {
                Log::warning("No data found for import {$importId}, checking for published data as fallback...");
                
                // Get the latest published data as fallback
                $latestPublication = MapPublication::where('status', 'published')
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                Log::info("Fallback publication check", [
                    'requested_import' => $importId,
                    'latest_pub_exists' => $latestPublication ? true : false,
                    'latest_pub_import_id' => $latestPublication ? $latestPublication->import_log_id : null
                ]);
                
                if ($latestPublication && $latestPublication->import_log_id) {
                    // Return the latest published data instead of empty result
                    $data = DataGulma::where('import_log_id', $latestPublication->import_log_id)->get();
                    Log::info("Returning published data as fallback", [
                        'requested_import' => $importId,
                        'published_import' => $latestPublication->import_log_id,
                        'records_found' => $data->count()
                    ]);
                }
            }
            
            // Always return in consistent format
            return response()->json([
                'success' => true,
                'import_id' => $importId,
                'import_log' => $importLog->only(['id', 'nama_file', 'tahun', 'bulan', 'minggu', 'status', 'wilayah_id']),
                'data' => $data->toArray(),
                'count' => $data->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getDataByImport', [
                'import_id' => $importId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * DEBUG: Check what data exists for an import
     */
    public function debugImport($importId)
    {
        try {
            Log::info("=== DEBUG IMPORT $importId ===");
            
            // Check ImportLog exists
            $importLog = ImportLog::find($importId);
            Log::info("ImportLog found: " . ($importLog ? 'YES' : 'NO'));
            if ($importLog) {
                Log::info("ImportLog data:", $importLog->toArray());
            }
            
            // Check raw count in database
            $count = DB::table('data_gulmas')->where('import_log_id', $importId)->count();
            Log::info("Raw DB count for import_log_id=$importId: $count");
            
            // Check first few records
            $samples = DB::table('data_gulmas')
                ->where('import_log_id', $importId)
                ->limit(5)
                ->get();
            Log::info("Sample records:", $samples->toArray());
            
            // Also check by tahun/bulan/minggu if import has them
            if ($importLog) {
                $count2 = DB::table('data_gulmas')
                    ->where('tahun', $importLog->tahun)
                    ->where('bulan', $importLog->bulan)
                    ->where('minggu', $importLog->minggu)
                    ->count();
                Log::info("Count by tahun/bulan/minggu: $count2");
            }
            
            return response()->json([
                'import_id' => $importId,
                'import_log_exists' => $importLog ? true : false,
                'import_log' => $importLog ? $importLog->toArray() : null,
                'data_count_by_import_log_id' => $count,
                'data_count_by_period' => $importLog ? DB::table('data_gulmas')
                    ->where('tahun', $importLog->tahun)
                    ->where('bulan', $importLog->bulan)
                    ->where('minggu', $importLog->minggu)
                    ->count() : null,
                'sample_records' => $samples->toArray(),
                'message' => 'Check browser console (F12) for detailed logs'
            ]);
        } catch (\Exception $e) {
            Log::error('Debug error: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
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
                DB::raw('AVG(umur) as avg_umur'),
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
                'avg_umur' => $query->avg('umur'),
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

        $publication = MapPublication::where('tahun', $request->tahun)
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

    /**
     * Fix missing import_log_id by linking DataGulma records to their ImportLog
     * This is a maintenance endpoint to fix data that was imported before import_log_id tracking was added
     */
    public function fixMissingImportLogIds()
    {
        Log::info('Starting fixMissingImportLogIds...');
        
        // Get all ImportLogs ordered by creation
        $imports = ImportLog::orderBy('created_at', 'asc')->get();
        
        $fixed = 0;
        $skipped = 0;
        
        foreach ($imports as $import) {
            // Get wilayah list from import
            $wilayahArray = explode(',', $import->wilayah_id);
            
            // Count records in this wilayah range that don't have import_log_id
            $orphanCount = DataGulma::whereIn('wilayah_id', $wilayahArray)
                ->whereNull('import_log_id')
                ->count();
            
            if ($orphanCount > 0) {
                // Assign these orphan records to this import
                $updated = DataGulma::whereIn('wilayah_id', $wilayahArray)
                    ->whereNull('import_log_id')
                    ->update(['import_log_id' => $import->id]);
                
                $fixed += $updated;
                Log::info("ImportLog {$import->id}: Fixed {$updated} orphan records");
            } else {
                $skipped++;
            }
        }
        
        // Check for any remaining orphans
        $remainingOrphans = DataGulma::whereNull('import_log_id')->count();
        
        return response()->json([
            'success' => true,
            'message' => 'Import log IDs fixed',
            'fixed' => $fixed,
            'skipped' => $skipped,
            'remaining_orphans' => $remainingOrphans,
            'total_imports' => $imports->count()
        ]);
    }

    /**
     * API: Ambil daftar file yang berhasil untuk periode tertentu
     */
    public function getFilesForPeriod(Request $request)
    {
        $tahun = $request->query('tahun');
        $bulan = $request->query('bulan');
        $minggu = $request->query('minggu');
        
        // Validasi periode
        if (!$tahun || !$bulan || !$minggu) {
            return response()->json(['files' => []]);
        }
        
        // Ambil semua file yang berhasil untuk periode ini
        $files = ImportLog::where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('minggu', $minggu)
            ->where('status', 'success')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($import) use ($tahun, $bulan, $minggu) {
                // Cek apakah file ini sedang dipublikasi untuk periode ini
                $publication = MapPublication::where('import_log_id', $import->id)
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->where('minggu', $minggu)
                    ->where('status', 'published')
                    ->first();
                
                return [
                    'id' => $import->id,
                    'name' => $import->nama_file,
                    'records' => $import->jumlah_berhasil,
                    'uploaded_at' => $import->created_at->format('d M Y H:i'),
                    'is_published' => $publication ? true : false,
                    'publication_id' => $publication?->id
                ];
            });
        
        return response()->json(['files' => $files]);
    }

    /**
     * API: Set file mana yang akan dipublikasi untuk periode tertentu
     */
    public function setPublication(Request $request)
    {
        $import_log_id = $request->input('import_log_id');
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');
        $minggu = $request->input('minggu');
        
        // Validasi input
        if (!$import_log_id || !$tahun || !$bulan || !$minggu) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak lengkap'
            ], 400);
        }
        
        // Validasi import log ada dan status success
        $importLog = ImportLog::find($import_log_id);
        if (!$importLog || $importLog->status !== 'success') {
            return response()->json([
                'success' => false,
                'message' => 'File tidak valid atau belum berhasil diimport'
            ], 400);
        }
        
        // Validasi periode sesuai
        if ($importLog->tahun != $tahun || $importLog->bulan != $bulan || $importLog->minggu != $minggu) {
            return response()->json([
                'success' => false,
                'message' => 'Periode tidak sesuai'
            ], 400);
        }
        
        try {
            // Mulai transaction untuk consistency
            DB::beginTransaction();
            
            Log::info('Setting publication for import_log_id: ' . $import_log_id . ' (' . $importLog->nama_file . ') for period ' . $tahun . '/' . $bulan . '/W' . $minggu);
            
            // Unpublish semua file lain untuk periode yang sama (gunakan periode columns)
            $oldPublications = MapPublication::where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->where('minggu', $minggu)
                ->where('status', 'published')
                ->where('import_log_id', '!=', $import_log_id)
                ->get();
            
            foreach ($oldPublications as $pub) {
                $pub->update(['status' => 'draft']);
                Log::info('Unpublished previous publication for period: ' . $tahun . '/' . $bulan . '/W' . $minggu . ' (Import ID: ' . $pub->import_log_id . ')');
            }
            
            // Set file baru sebagai published dengan periode tracking
            $publication = MapPublication::updateOrCreate(
                ['tahun' => $tahun, 'bulan' => $bulan, 'minggu' => $minggu],
                [
                    'import_log_id' => $import_log_id,
                    'status' => 'published',
                    'published_at' => now(),
                    'published_by' => auth()->id()
                ]
            );
            
            // PENTING: Clear GeoJSON cache karena data publikasi untuk periode ini berubah
            // Cache keys: geojson_wgs84_wil_16 hingga geojson_wgs84_wil_23
            for ($wil = 16; $wil <= 23; $wil++) {
                $cacheKey = "geojson_wgs84_wil_{$wil}";
                Cache::forget($cacheKey);
                Log::info("Cleared cache: {$cacheKey} for period publication change");
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Publikasi berhasil diperbarui',
                'publication' => $publication,
                'import_id' => $import_log_id,
                'nama_file' => $importLog->nama_file,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'minggu' => $minggu
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Publication update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui publikasi: ' . $e->getMessage()
            ], 500);
        }
    }
}

