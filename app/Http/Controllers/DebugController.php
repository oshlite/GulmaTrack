<?php
namespace App\Http\Controllers;

class DebugController extends Controller
{
    public function checkPublications()
    {
        $pubs = \App\Models\MapPublication::orderBy('published_at', 'desc')
            ->get(['id', 'tahun', 'bulan', 'minggu', 'import_log_id', 'published_at', 'status']);
        
        $imports = \App\Models\ImportLog::where('status', 'success')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->orderBy('minggu', 'desc')
            ->get(['id', 'tahun', 'bulan', 'minggu', 'nama_file', 'status']);
        
        return response()->json([
            'publications' => $pubs,
            'import_logs' => $imports->take(10)
        ]);
    }
    
    /**
     * Restore all publications that were previously published (restore from draft)
     * Each periode tahun/bulan/minggu should have its latest import as published
     */
    public function restorePublications()
    {
        // Find all unique periods that have draft publications
        $drafts = \App\Models\MapPublication::where('status', 'draft')
            ->select('tahun', 'bulan', 'minggu')
            ->groupBy('tahun', 'bulan', 'minggu')
            ->get();
        
        $restored = 0;
        
        foreach ($drafts as $period) {
            // Get latest import for this period
            $latest = \App\Models\ImportLog::where('tahun', $period->tahun)
                ->where('bulan', $period->bulan)
                ->where('minggu', $period->minggu)
                ->where('status', 'success')
                ->latest('created_at')
                ->first();
            
            if ($latest) {
                // Restore publication
                $publication = \App\Models\MapPublication::where('tahun', $period->tahun)
                    ->where('bulan', $period->bulan)
                    ->where('minggu', $period->minggu)
                    ->first();
                
                if ($publication) {
                    $publication->update([
                        'status' => 'published',
                        'import_log_id' => $latest->id
                    ]);
                    $restored++;
                    \Log::info("✅ Restored: {$period->tahun}/{$period->bulan}/W{$period->minggu}");
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "Restored {$restored} publications",
            'restored' => $restored
        ]);
    }
}
