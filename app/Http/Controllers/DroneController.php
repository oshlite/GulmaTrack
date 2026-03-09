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
     * Generate PDF thumbnail - Multi-strategy approach
     * 1. Try Ghostscript direct command (no Python dependency!)
     * 2. Fallback: ImageMagick/convert jika available
     * 3. Fallback: Generate dari judul/lokasi dengan visual design
     * Thumbnail di-cache saat upload, jadi no performance impact
     */
    private function generateThumbnail(Drone $drone)
    {
        $pdfPath = storage_path('app/public/' . $drone->pdf_path);
        
        if (!file_exists($pdfPath)) {
            \Log::warning('PDF file not found for drone: ' . $drone->id . ' at ' . $pdfPath);
            return;
        }
        
        // Ensure thumbnail directory ada
        $thumbnailDir = storage_path('app/public/thumbnails');
        if (!is_dir($thumbnailDir)) {
            mkdir($thumbnailDir, 0755, true);
        }
        
        $thumbnailPath = $thumbnailDir . '/thumb_' . $drone->id . '.jpg';
        
        // Skip jika sudah ada (caching strategy)
        if (file_exists($thumbnailPath)) {
            return;
        }
        
        try {
            // ✅ STRATEGY 1: Try Ghostscript direct command (no Python!)
            if ($this->tryGhostscript($pdfPath, $thumbnailPath)) {
                \Log::debug('Thumbnail generated via Ghostscript for drone: ' . $drone->id);
                return;
            }
            
            // ✅ STRATEGY 2: Try ImageMagick convert command
            if ($this->tryImageMagickConvert($pdfPath, $thumbnailPath)) {
                \Log::debug('Thumbnail generated via ImageMagick for drone: ' . $drone->id);
                return;
            }
            
            // ✅ STRATEGY 3: Fallback - Generate dari judul (if all else fails)
            if ($this->generateDesignThumbnail($drone, $thumbnailPath)) {
                \Log::debug('Design thumbnail generated for drone: ' . $drone->id);
                return;
            }
            
        } catch (\Exception $e) {
            \Log::error('Thumbnail generation failed for drone ' . $drone->id . ': ' . $e->getMessage());
            // Silently fail - fallback ke placeholder SVG saat serve
        }
    }
    
    /**
     * Try generate thumbnail directly using Ghostscript (NO Python needed!)
     * Using shell_exec() for better Windows compatibility
     */
    private function tryGhostscript($pdfPath, $outputPath)
    {
        try {
            // Find Ghostscript executable
            $gsPath = $this->findGhostscript();
            if (!$gsPath) {
                \Log::debug('Ghostscript not found');
                return false;
            }
            
            // Create temp PNG first
            $tempPng = storage_path('app/public/temp_' . uniqid() . '.png');
            
            try {
                // Build command untuk Ghostscript
                // Using shell_exec for better Windows compatibility
                $command = sprintf(
                    '"%s" -q -dNOPAUSE -dBATCH -dSAFER -sDEVICE=png256 -r120 -sOutputFile="%s" -dFirstPage=1 -dLastPage=1 "%s"',
                    $gsPath,
                    $tempPng,
                    $pdfPath
                );
                
                // Execute using shell_exec untuk better Windows support
                @shell_exec($command . ' 2>&1');
                
                // Check di file was created
                if (!file_exists($tempPng) || filesize($tempPng) < 1000) {
                    return false;
                }
                
                // Convert PNG to JPEG with white background
                if (!$this->convertImageToThumbnail($tempPng, $outputPath, 250, 150)) {
                    return false;
                }
                
                // Cleanup
                if (file_exists($tempPng)) {
                    @unlink($tempPng);
                }
                
                return file_exists($outputPath) && filesize($outputPath) > 1000;
                
            } finally {
                // Ensure cleanup
                if (file_exists($tempPng)) {
                    @unlink($tempPng);
                }
            }
        } catch (\Exception $e) {
            \Log::debug('Ghostscript exception: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Try generate thumbnail using ImageMagick convert
     */
    private function tryImageMagickConvert($pdfPath, $outputPath)
    {
        try {
            // Try convert command (ImageMagick)
            $command = sprintf(
                'convert "%s"[0] -resize 250x150 -background white -gravity center -extent 250x150 -quality 75 "%s" 2>&1',
                $pdfPath,
                $outputPath
            );
            
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0 && file_exists($outputPath) && filesize($outputPath) > 1000) {
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Find Ghostscript executable
     */
    private function findGhostscript()
    {
        $possiblePaths = [
            'D:\\AppData\\gs10.04.0\\bin\\gswin64c.exe',
            'D:\\Program Files\\gs\\gs10.04.0\\bin\\gswin64c.exe',
            'C:\\Program Files\\gs\\gs10.04.0\\bin\\gswin64c.exe',
            'C:\\Program Files (x86)\\gs\\gs10.04.0\\bin\\gswin64c.exe',
            'gswin64c.exe', // System PATH
            'gs.exe',
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path) || $this->commandExists($path)) {
                return $path;
            }
        }
        
        return null;
    }
    
    /**
     * Check if command exists in system PATH
     */
    private function commandExists($command)
    {
        // For Windows
        $output = @shell_exec('where ' . $command . ' 2>&1');
        return !empty($output);
    }
    
    /**
     * Convert image to thumbnail (fit to 250x150 with white background)
     */
    private function convertImageToThumbnail($inputPath, $outputPath, $width = 250, $height = 150)
    {
        try {
            $image = imagecreatefrompng($inputPath);
            if (!$image) {
                throw new \Exception('Failed to load PNG image');
            }
            
            // Get original dimensions
            $origWidth = imagesx($image);
            $origHeight = imagesy($image);
            
            // Create canvas with white background
            $canvas = imagecreatetruecolor($width, $height);
            $white = imagecolorallocate($canvas, 255, 255, 255);
            imagefill($canvas, 0, 0, $white);
            
            // Calculate scaling to fit within canvas
            $scale = min($width / $origWidth, $height / $origHeight);
            $newWidth = (int)($origWidth * $scale);
            $newHeight = (int)($origHeight * $scale);
            
            // Center position
            $x = (int)(($width - $newWidth) / 2);
            $y = (int)(($height - $newHeight) / 2);
            
            // Resize and paste onto canvas
            imagecopyresampled(
                $canvas, $image,
                $x, $y, 0, 0,
                $newWidth, $newHeight,
                $origWidth, $origHeight
            );
            
            // Save as JPEG
            imagejpeg($canvas, $outputPath, 75);
            imagedestroy($canvas);
            imagedestroy($image);
            
            return true;
        } catch (\Exception $e) {
            \Log::debug('Image conversion error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate thumbnail design dari judul + lokasi (fallback yang reliable!)
     * Menggunakan GD Library (built-in PHP) untuk create visual design
     */
    private function generateDesignThumbnail(Drone $drone, $outputPath)
    {
        try {
            $width = 250;
            $height = 150;
            
            // Create blank image dengan gradient effect
            $image = imagecreatetruecolor($width, $height);
            
            // Color palette untuk drone planning
            $colors = [
                'bg_top' => imagecolorallocate($image, 18, 130, 65),      // Green (#128241)
                'bg_bottom' => imagecolorallocate($image, 13, 92, 46),    // Dark green  
                'text_main' => imagecolorallocate($image, 255, 255, 255), // White
                'text_accent' => imagecolorallocate($image, 251, 169, 25) // Orange (#FBA919)
            ];
            
            // Draw gradient background (simple horizontal gradient)
            for ($i = 0; $i < $height; $i++) {
                $ratio = $i / $height;
                $r = (int)(18 + (13 - 18) * $ratio);
                $g = (int)(130 + (92 - 130) * $ratio);
                $b = (int)(65 + (46 - 65) * $ratio);
                $lineColor = imagecolorallocate($image, $r, $g, $b);
                imageline($image, 0, $i, $width, $i, $lineColor);
            }
            
            // Add decorative border
            imagerectangle($image, 0, 0, $width-1, $height-1, $colors['text_accent']);
            
            // Register font (gunakan built-in GD fonts)
            $titleFont = 5;      // Large font
            $subtitleFont = 3;   // Medium font
            
            // Title (judul drone)
            $titleText = substr($drone->judul, 0, 40); // Truncate jika terlalu panjang
            
            // Hitung text width untuk center
            $titleBox = imagettfbbox(12, 0, __DIR__ . '/../../resources/fonts/Poppins-SemiBold.ttf', $titleText);
            $titleWidth = $titleBox[2] - $titleBox[0];
            $titleX = ($width - $titleWidth) / 2;
            
            // Coba gunakan TTF font kalau ada, fallback ke built-in
            $ttfPath = resource_path('fonts/Poppins-SemiBold.ttf');
            
            if (file_exists($ttfPath)) {
                // Use TTF font untuk lebih cantik
                imagettftext($image, 14, 0, 10, 45, $colors['text_main'], $ttfPath, $titleText);
                
                // Subtitle (lokasi + gulma %)
                $subtitleText = $drone->lokasi . ' • ' . ($drone->persen_gulma ?? 'N/A') . '% Gulma';
                imagettftext($image, 10, 0, 10, 75, $colors['text_accent'], $ttfPath, $subtitleText);
                
                // Footer hint
                imagettftext($image, 8, 0, 10, 135, $colors['text_main'], $ttfPath, '🚁 Drone Pengendalian Gulma');
            } else {
                // Fallback ke built-in GD fonts (bawaan, jadi pasti ada)
                imagestring($image, 5, 15, 25, $titleText, $colors['text_main']);
                imagestring($image, 3, 15, 50, $drone->lokasi . ' - ' . ($drone->persen_gulma ?? 'N/A') . '%', $colors['text_accent']);
                imagestring($image, 2, 15, 80, 'Drone Perencanaan Pengendalian', $colors['text_main']);
            }
            
            // Add icon/emoji indicator (text-based)
            imagestring($image, 5, ($width - 25), 60, '🚁', $colors['text_accent']);
            
            // Save sebagai JPEG
            imagejpeg($image, $outputPath, 75);
            imagedestroy($image);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Design thumbnail generation failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Placeholder SVG untuk thumbnail yang belum ada
     * Sekarang menampilkan teks informatif tentang drone
     */
    private function getPlaceholderSvg()
    {
        $svg = <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 250 150">
    <!-- Gradient background -->
    <defs>
        <linearGradient id="bgGradient" x1="0%" y1="0%" x2="0%" y2="100%">
            <stop offset="0%" style="stop-color:#128241;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#0d5c2e;stop-opacity:1" />
        </linearGradient>
    </defs>
    
    <!-- Background -->
    <rect width="250" height="150" fill="url(#bgGradient)"/>
    
    <!-- Border accent -->
    <rect x="0" y="0" width="250" height="150" fill="none" stroke="#FBA919" stroke-width="2"/>
    
    <!-- Drone icon/emoji -->
    <text x="125" y="35" font-family="Arial, sans-serif" font-size="32" text-anchor="middle" fill="#FBA919">🚁</text>
    
    <!-- Main text -->
    <text x="125" y="70" font-family="Arial, sans-serif" font-size="14" font-weight="bold" text-anchor="middle" fill="white">Drone Planning</text>
    
    <!-- Subtitle -->
    <text x="125" y="90" font-family="Arial, sans-serif" font-size="11" text-anchor="middle" fill="#D6DF20">Pengendalian Gulma</text>
    
    <!-- Status -->
    <text x="125" y="130" font-family="Arial, sans-serif" font-size="9" text-anchor="middle" fill="#e0e0e0">Generating thumbnail...</text>
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
