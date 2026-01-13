<?php

namespace App\Http\Controllers;

use App\Models\Drone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DroneController extends Controller
{
    /**
     * Tampilkan halaman admin drone
     */
    public function adminIndex()
    {
        $drones = Drone::orderBy('created_at', 'desc')->paginate(10);
        $droneUploadTerbaru = Drone::orderBy('created_at', 'desc')->first();
        $totalPdf = Drone::count();
        return view('admin.drone', compact('drones', 'droneUploadTerbaru', 'totalPdf'));
    }

    /**
     * Upload drone PDF
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'tanggal_perencanaan' => 'required|date',
            'persen_gulma' => 'nullable|numeric|min:0|max:100',
            'pdf_file' => 'required|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        try {
            // Upload PDF
            if ($request->hasFile('pdf_file')) {
                $file = $request->file('pdf_file');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $path = $file->storeAs('drones', $filename, 'public');

                // Simpan ke database
                Drone::create([
                    'judul' => $validated['judul'],
                    'lokasi' => $validated['lokasi'],
                    'tanggal_perencanaan' => $validated['tanggal_perencanaan'],
                    'persen_gulma' => $validated['persen_gulma'] ?? null,
                    'pdf_path' => $path,
                    'pdf_filename' => $filename,
                    'user_id' => null, // Admin upload untuk ditampilkan ke user
                ]);

                return redirect()->route('admin.drone.index')
                    ->with('success', 'File drone berhasil diupload!');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.drone.index')
                ->with('error', 'Gagal mengupload file: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan halaman drone untuk user
     */
    public function userIndex()
    {
        $drones = Drone::orderBy('tanggal_perencanaan', 'desc')->get();
        return view('pages.drone', compact('drones'));
    }

    /**
     * Download PDF drone
     */
    public function download($id)
    {
        $drone = Drone::findOrFail($id);
        
        $filePath = storage_path('app/public/' . $drone->pdf_path);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan!');
        }

        return response()->download($filePath, $drone->pdf_filename);
    }

    /**
     * Tampilkan PDF drone secara inline (bukan download)
     */
    public function view($id)
    {
        $drone = Drone::findOrFail($id);
        
        $filePath = storage_path('app/public/' . $drone->pdf_path);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan!');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $drone->pdf_filename . '"',
        ]);
    }

    /**
     * Hapus drone
     */
    public function destroy($id)
    {
        $drone = Drone::findOrFail($id);
        
        // Hapus file dari storage
        if (Storage::disk('public')->exists($drone->pdf_path)) {
            Storage::disk('public')->delete($drone->pdf_path);
        }

        $drone->delete();

        return redirect()->route('admin.drone.index')
            ->with('success', 'Drone berhasil dihapus!');
    }
}
