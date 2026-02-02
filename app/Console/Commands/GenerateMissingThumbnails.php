<?php

namespace App\Console\Commands;

use App\Models\Drone;
use Illuminate\Console\Command;

class GenerateMissingThumbnails extends Command
{
    protected $signature = 'thumbnails:generate-missing';
    protected $description = 'Generate thumbnails untuk semua PDF yang belum ada thumbnailnya';

    public function handle()
    {
        $drones = Drone::all();
        $count = 0;

        foreach ($drones as $drone) {
            $thumbnailPath = storage_path('app/public/thumbnails/thumb_' . $drone->id . '.jpg');
            
            // Skip jika sudah ada
            if (file_exists($thumbnailPath)) {
                $this->info("✓ Drone {$drone->id}: thumbnail sudah ada");
                continue;
            }

            // Generate thumbnail
            try {
                $this->generateThumbnail($drone);
                $this->info("✓ Drone {$drone->id}: thumbnail berhasil dibuat");
                $count++;
            } catch (\Exception $e) {
                $this->error("✗ Drone {$drone->id}: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("Selesai! {$count} thumbnail berhasil dibuat.");
    }

    private function generateThumbnail(Drone $drone)
    {
        $pdfPath = storage_path('app/public/' . $drone->pdf_path);
        
        if (!file_exists($pdfPath)) {
            throw new \Exception('PDF file tidak ditemukan');
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
        
        // Gunakan Python + pdf2image untuk extract real PDF content
        $pythonScript = base_path('scripts/pdf_to_thumbnail.py');
        
        if (!file_exists($pythonScript)) {
            throw new \Exception('Python script tidak ditemukan');
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
            throw new \Exception('PDF to thumbnail conversion failed: ' . implode(' ', $output));
        }
        
        if (!file_exists($thumbnailPath)) {
            throw new \Exception('Thumbnail file tidak terbuat');
        }
    }
}
