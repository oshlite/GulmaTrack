<?php

namespace App\Http\Controllers;

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
     * Tampilkan dashboard admin
     */
    public function dashboard()
    {
        // Data statistik dummy (bisa diganti dengan query ke database)
        $totalDataGulma = 0;
        $wilayahAktif = 0;
        $totalTanaman = 0;
        $importTerbaru = collect([]); // Empty collection

        // Jika sudah punya models, bisa uncomment dan sesuaikan:
        // $totalDataGulma = DataGulma::count();
        // $wilayahAktif = Wilayah::where('status', 'aktif')->count();
        // $totalTanaman = Tanaman::count();
        // $importTerbaru = CsvImportLog::latest()->limit(5)->get();

        return view('admin.dashboard', [
            'totalDataGulma' => $totalDataGulma,
            'wilayahAktif' => $wilayahAktif,
            'totalTanaman' => $totalTanaman,
            'importTerbaru' => $importTerbaru,
        ]);
    }

    /**
     * Handle CSV upload
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

        // TODO: Proses CSV file di sini
        // Contoh: Import data dari CSV ke database
        
        return redirect()->route('admin.dashboard')->with('success', 'File CSV sedang diproses');
    }
}