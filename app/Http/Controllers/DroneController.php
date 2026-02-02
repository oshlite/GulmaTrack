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
     * Upload drone PDF + Generate thumbnail (cached at upload time)
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
                $drone = Drone::create([
                    'judul' => $validated['judul'],
                    'lokasi' => $validated['lokasi'],
                    'tanggal_perencanaan' => $validated['tanggal_perencanaan'],
                    'persen_gulma' => $validated['persen_gulma'] ?? null,
                    'pdf_path' => $path,
                    'pdf_filename' => $filename,
                    'user_id' => null,
                ]);

                // Generate + cache thumbnail sekali saat upload
                try {
                    $this->generateThumbnail($drone);
                } catch (\Exception $e) {
                    // Optional - tetap lanjut jika thumbnail gagal
                    \Log::warning('Thumbnail generation failed: ' . $e->getMessage());
                }

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
            'Content-Disposition' => 'inline; filename="' . basename($drone->pdf_path) . '"',
            'Cache-Control' => 'public, max-age=0',
            'Pragma' => 'public',
            'X-Content-Type-Options' => 'nosniff'
        ]);
    }

    /**
     * Serve cached PDF thumbnail (generated saat upload, tinggal serve file!)
     * Response time: <1ms (no processing, just file serving!)
     * Fallback ke placeholder SVG jika belum ada
     */
    public function thumbnail($id)
    {
        $drone = Drone::findOrFail($id);
        
        // Path thumbnail yang sudah di-cache saat upload
        $thumbnailPath = storage_path('app/public/thumbnails/thumb_' . $drone->id . '.jpg');
        
        // Jika sudah ada, serve langsung! (super cepat)
        if (file_exists($thumbnailPath)) {
            return response()->file($thumbnailPath, [
                'Content-Type' => 'image/jpeg',
                'Cache-Control' => 'public, max-age=31536000' // Cache 1 year
            ]);
        }
        
        // Fallback: placeholder SVG
        return $this->getPlaceholderSvg();
    }
    
    /**
     * Generate PDF thumbnail dari page 1 - caching strategy
     * Menggunakan Python + pdf2image untuk extract real PDF content
     * Reliable, cepat, dan zero dependencies headache
     */
    private function generateThumbnail(Drone $drone)
    {
        $pdfPath = storage_path('app/public/' . $drone->pdf_path);
        
        if (!file_exists($pdfPath)) {
            return;
        }
        
        // Ensure thumbnail directory ada
        $thumbnailDir = storage_path('app/public/thumbnails');
        if (!is_dir($thumbnailDir)) {
            mkdir($thumbnailDir, 0755, true);
        }
        
        $thumbnailPath = $thumbnailDir . '/thumb_' . $drone->id . '.jpg';
        
        // Skip jika sudah ada
        if (file_exists($thumbnailPath)) {
            return;
        }
        
        try {
            // Gunakan Python + pdf2image untuk real thumbnail dari PDF content
            $pythonScript = base_path('scripts/pdf_to_thumbnail.py');
            
            if (!file_exists($pythonScript)) {
                \Log::error('Python script not found: ' . $pythonScript);
                return;
            }
            
            // Command: python pdf_to_thumbnail.py <input_pdf> <output_jpg>
            $command = sprintf(
                'python "%s" "%s" "%s"',
                $pythonScript,
                escapeshellarg($pdfPath),
                escapeshellarg($thumbnailPath)
            );
            
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                \Log::error('Thumbnail generation failed', [
                    'drone_id' => $drone->id,
                    'output' => implode(' ', $output),
                    'return_code' => $returnCode
                ]);
                return;
            }
            
            if (file_exists($thumbnailPath)) {
                \Log::debug('Thumbnail generated for drone: ' . $drone->id);
            }
        } catch (\Exception $e) {
            // Log error tapi jangan throw - fallback ke placeholder
            \Log::error('Thumbnail generation exception: ' . $e->getMessage());
        }
    }
    
    /**
     * Placeholder SVG untuk thumbnail yang belum ada
     */
    private function getPlaceholderSvg()
    {
        $svg = <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 250 150">
    <rect width="250" height="150" fill="#f5f5f5" stroke="#dcdcdc" stroke-width="1"/>
    <text x="125" y="75" font-family="Arial, sans-serif" font-size="24" font-weight="bold" text-anchor="middle" dominant-baseline="middle" fill="#999999">PDF</text>
</svg>
SVG;
        
        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=3600'
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
        
        // Hapus thumbnail jika ada
        $thumbnailPath = 'public/thumbnails/thumb_' . $drone->id . '.jpg';
        if (Storage::exists($thumbnailPath)) {
            Storage::delete($thumbnailPath);
        }

        $drone->delete();

        return response()->json(['success' => true]);
    }

    /**
     * API endpoint untuk load drones via AJAX (pagination)
     */
    public function getDronesPaginated()
    {
        $drones = Drone::orderBy('created_at', 'desc')->paginate(10);
        
        return response()->json([
            'drones' => $drones->items(),
            'current_page' => $drones->currentPage(),
            'last_page' => $drones->lastPage(),
            'per_page' => $drones->perPage(),
            'total' => $drones->total(),
            'from' => $drones->firstItem(),
            'to' => $drones->lastItem(),
        ]);
    }
}
