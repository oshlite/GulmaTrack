<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DataGulma;
use App\Models\MapPublication;

class CleanupUnpublished extends Command
{
    protected $signature = 'cleanup:unpublished';
    protected $description = 'Delete unpublished import data, keep only published data';

    public function handle()
    {
        $this->info('====== CLEANUP UNPUBLISHED IMPORTS ======');

        // Get all published import_log_ids
        $published = MapPublication::where('status', 'published')->pluck('import_log_id')->toArray();
        $this->line("\nPublished import_log_ids: " . implode(', ', $published));

        $this->line("\n--- BEFORE ---");
        $this->line("Total DataGulma records: " . DataGulma::count());

        // Show breakdown BEFORE
        $countsBefore = DataGulma::groupBy('import_log_id')
            ->selectRaw('import_log_id, count(*) as total')
            ->orderBy('import_log_id', 'desc')
            ->get();

        foreach ($countsBefore as $item) {
            $pub = MapPublication::where('import_log_id', $item->import_log_id)->first();
            $status = $pub ? '✓ PUBLISHED' : '✗ UNPUBLISHED';
            $this->line("  Import {$item->import_log_id}: {$item->total} records [{$status}]");
        }

        // Delete DataGulma records that are NOT in published imports
        $this->line("\n--- DELETING UNPUBLISHED DATA ---");
        $deleted = DataGulma::whereNotIn('import_log_id', $published)->delete();
        $this->line("Deleted $deleted unpublished DataGulma records");

        $this->line("\n--- AFTER ---");
        $this->line("Total DataGulma records: " . DataGulma::count());

        // Show breakdown AFTER
        $countsAfter = DataGulma::groupBy('import_log_id')
            ->selectRaw('import_log_id, count(*) as total')
            ->orderBy('import_log_id', 'desc')
            ->get();

        foreach ($countsAfter as $item) {
            $pub = MapPublication::where('import_log_id', $item->import_log_id)->first();
            $status = $pub ? '✓ PUBLISHED' : '✗ UNPUBLISHED';
            $period = $pub ? "{$pub->tahun}/{$pub->bulan}/W{$pub->minggu}" : 'UNKNOWN';
            $this->line("  Import {$item->import_log_id} ({$period}): {$item->total} records [{$status}]");
        }

        $this->info("\n✅ Cleanup complete!");
    }
}
