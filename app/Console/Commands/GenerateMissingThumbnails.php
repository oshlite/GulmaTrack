<?php

namespace App\Console\Commands;

use App\Models\Drone;
use App\Http\Controllers\DroneController;
use Illuminate\Console\Command;

class GenerateMissingThumbnails extends Command
{
    protected $signature = 'thumbnails:generate-missing {--force : Regenerate semua thumbnails, termasuk yang sudah ada}';
    protected $description = 'Generate thumbnails untuk semua PDF (dengan fallback strategy)';

    public function handle()
    {
        $force = $this->option('force');
        $drones = Drone::all();
        $count = 0;
        $skipped = 0;

        $this->info('🚁 Generating drone thumbnails...'.PHP_EOL);
        
        $progressBar = $this->output->createProgressBar(count($drones));
        $progressBar->start();

        foreach ($drones as $drone) {
            $thumbnailPath = storage_path('app/public/thumbnails/thumb_' . $drone->id . '.jpg');
            
            // Skip jika sudah ada dan tidak force
            if (!$force && file_exists($thumbnailPath) && filesize($thumbnailPath) > 1000) {
                $skipped++;
                $progressBar->advance();
                continue;
            }

            // Generate thumbnail menggunakan method dari controller
            try {
                $this->generateThumbnailForDrone($drone);
                $count++;
            } catch (\Exception $e) {
                $this->warn("  ⚠️  Drone {$drone->id}: " . $e->getMessage());
            }
            
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);
        
        if ($force) {
            $this->info("✅ Selesai! {$count} thumbnail berhasil dibuat/diperbarui.");
        } else {
            $this->info("✅ Selesai! {$count} thumbnail baru dibuat, {$skipped} sudah ada.");
        }
    }

    /**
     * Generate thumbnail dengan multi-strategy approach (Ghostscript → ImageMagick → Design)
     */
    private function generateThumbnailForDrone(Drone $drone)
    {
        $pdfPath = storage_path('app/public/' . $drone->pdf_path);
        
        if (!file_exists($pdfPath)) {
            throw new \Exception('PDF file tidak ditemukan: ' . $pdfPath);
        }
        
        // Ensure thumbnail directory ada
        $thumbnailDir = storage_path('app/public/thumbnails');
        if (!is_dir($thumbnailDir)) {
            mkdir($thumbnailDir, 0755, true);
        }
        
        $thumbnailPath = $thumbnailDir . '/thumb_' . $drone->id . '.jpg';
        
        // ✅ STRATEGY 1: Try Ghostscript direct (NO Python!)
        if ($this->tryGhostscript($pdfPath, $thumbnailPath)) {
            return;
        }
        
        // ✅ STRATEGY 2: Try ImageMagick convert
        if ($this->tryImageMagickConvert($pdfPath, $thumbnailPath)) {
            return;
        }
        
        // ✅ STRATEGY 3: Fallback - Generate design image dari judul
        if ($this->generateDesignThumbnail($drone, $thumbnailPath)) {
            return;
        }
        
        throw new \Exception('Semua strategy gagal untuk drone ' . $drone->id);
    }
    
    /**
     * Try Ghostscript direct command using shell_exec
     */
    private function tryGhostscript($pdfPath, $outputPath)
    {
        try {
            $gsPath = $this->findGhostscript();
            if (!$gsPath) {
                return false;
            }
            
            $tempPng = storage_path('app/public/temp_' . uniqid() . '.png');
            
            try {
                $command = sprintf(
                    '"%s" -q -dNOPAUSE -dBATCH -dSAFER -sDEVICE=png256 -r120 -sOutputFile="%s" -dFirstPage=1 -dLastPage=1 "%s"',
                    $gsPath,
                    $tempPng,
                    $pdfPath
                );
                
                @shell_exec($command . ' 2>&1');
                
                if (!file_exists($tempPng) || filesize($tempPng) < 1000) {
                    return false;
                }
                
                if (!$this->convertImageToThumbnail($tempPng, $outputPath, 250, 150)) {
                    return false;
                }
                
                if (file_exists($tempPng)) {
                    @unlink($tempPng);
                }
                
                return file_exists($outputPath) && filesize($outputPath) > 1000;
                
            } finally {
                if (file_exists($tempPng)) {
                    @unlink($tempPng);
                }
            }
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Try ImageMagick convert
     */
    private function tryImageMagickConvert($pdfPath, $outputPath)
    {
        try {
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
     * Find Ghostscript
     */
    private function findGhostscript()
    {
        $possiblePaths = [
            'D:\\AppData\\gs10.04.0\\bin\\gswin64c.exe',
            'D:\\Program Files\\gs\\gs10.04.0\\bin\\gswin64c.exe',
            'C:\\Program Files\\gs\\gs10.04.0\\bin\\gswin64c.exe',
            'C:\\Program Files (x86)\\gs\\gs10.04.0\\bin\\gswin64c.exe',
            'gswin64c.exe',
            'gs.exe',
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        return null;
    }
    
    /**
     * Convert image to thumbnail
     */
    private function convertImageToThumbnail($inputPath, $outputPath, $width = 250, $height = 150)
    {
        try {
            $image = imagecreatefrompng($inputPath);
            if (!$image) {
                return false;
            }
            
            $origWidth = imagesx($image);
            $origHeight = imagesy($image);
            
            $canvas = imagecreatetruecolor($width, $height);
            $white = imagecolorallocate($canvas, 255, 255, 255);
            imagefill($canvas, 0, 0, $white);
            
            $scale = min($width / $origWidth, $height / $origHeight);
            $newWidth = (int)($origWidth * $scale);
            $newHeight = (int)($origHeight * $scale);
            
            $x = (int)(($width - $newWidth) / 2);
            $y = (int)(($height - $newHeight) / 2);
            
            imagecopyresampled(
                $canvas, $image,
                $x, $y, 0, 0,
                $newWidth, $newHeight,
                $origWidth, $origHeight
            );
            
            imagejpeg($canvas, $outputPath, 75);
            imagedestroy($canvas);
            imagedestroy($image);
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Generate thumbnail design dari judul + lokasi (reliable fallback!)
     */
    private function generateDesignThumbnail(Drone $drone, $outputPath)
    {
        try {
            $width = 250;
            $height = 150;
            
            // Create blank image dengan gradient effect
            $image = imagecreatetruecolor($width, $height);
            
            // Color palette
            $colors = [
                'text_main' => imagecolorallocate($image, 255, 255, 255),      // White
                'text_accent' => imagecolorallocate($image, 251, 169, 25),     // Orange
                'bg_top' => imagecolorallocate($image, 18, 130, 65),           // Green
                'bg_bottom' => imagecolorallocate($image, 13, 92, 46)          // Dark green
            ];
            
            // Draw gradient background
            for ($i = 0; $i < $height; $i++) {
                $ratio = $i / $height;
                $r = (int)(18 + (13 - 18) * $ratio);
                $g = (int)(130 + (92 - 130) * $ratio);
                $b = (int)(65 + (46 - 65) * $ratio);
                $lineColor = imagecolorallocate($image, $r, $g, $b);
                imageline($image, 0, $i, $width, $i, $lineColor);
            }
            
            // Add border
            imagerectangle($image, 0, 0, $width-1, $height-1, $colors['text_accent']);
            
            // Title
            $titleText = substr($drone->judul, 0, 35);
            imagestring($image, 5, 12, 20, $titleText, $colors['text_main']);
            
            // Subtitle - Lokasi
            $subtitleText = 'Lokasi: ' . substr($drone->lokasi, 0, 30);
            imagestring($image, 3, 12, 50, $subtitleText, $colors['text_accent']);
            
            // Gulma percentage
            $gulmaText = ($drone->persen_gulma ?? 'N/A') . '% Gulma';
            imagestring($image, 3, 12, 70, $gulmaText, $colors['text_main']);
            
            // Footer
            imagestring($image, 2, 12, 130, '🚁 Drone Perencanaan Pengendalian', $colors['text_accent']);
            
            // Save as JPEG
            imagejpeg($image, $outputPath, 75);
            imagedestroy($image);
            
            return file_exists($outputPath) && filesize($outputPath) > 1000;
        } catch (\Exception $e) {
            return false;
        }
    }
}
