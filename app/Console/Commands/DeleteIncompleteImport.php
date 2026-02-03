<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DataGulma;
use App\Models\ImportLog;
use App\Models\MapPublication;
use Illuminate\Support\Facades\DB;

class DeleteIncompleteImport extends Command
{
    protected $signature = 'import:delete-incomplete {import_id} {--force}';
    protected $description = 'Delete an incomplete import and its data (for re-importing)';

    public function handle()
    {
        $importId = $this->argument('import_id');
        $force = $this->option('force');

        $import = ImportLog::find($importId);
        if (!$import) {
            $this->error("Import {$importId} not found!");
            return 1;
        }

        $this->info("=== DELETE INCOMPLETE IMPORT ===\n");
        $this->line("Import ID: {$importId}");
        $this->line("File: {$import->nama_file}");
        $this->line("Period: {$import->tahun}/{$import->bulan}/W{$import->minggu}");
        $this->line("Records: {$import->jumlah_berhasil}");

        // Count related data
        $dataCount = DataGulma::where('import_log_id', $importId)->count();
        $pubCount = MapPublication::where('import_log_id', $importId)->count();

        $this->line("\nRelated data to delete:");
        $this->line("  - DataGulma records: $dataCount");
        $this->line("  - MapPublication records: $pubCount");

        if (!$force) {
            if (!$this->confirm("\n⚠️  Are you sure you want to DELETE this import and all related data?")) {
                $this->info("Cancelled.");
                return 0;
            }
        }

        DB::beginTransaction();
        try {
            // Delete in correct order (foreign keys)
            DataGulma::where('import_log_id', $importId)->delete();
            MapPublication::where('import_log_id', $importId)->delete();
            $import->delete();

            DB::commit();

            $this->info("\n✅ Successfully deleted:");
            $this->line("  - DataGulma records: $dataCount");
            $this->line("  - MapPublication records: $pubCount");
            $this->line("  - ImportLog ID: {$importId}");
            $this->info("\nYou can now re-upload the correct file with same period.");

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error deleting import: " . $e->getMessage());
            return 1;
        }
    }
}
