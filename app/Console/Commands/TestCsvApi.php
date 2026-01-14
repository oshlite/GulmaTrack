<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DataGulma;
use App\Models\MapPublication;

class TestCsvApi extends Command
{
    protected $signature = 'test:csv-api';
    protected $description = 'Test CSV API functionality';

    public function handle()
    {
        $this->info('Testing CSV API endpoints...');
        $this->line('==============================');

        // Test 1: Check if DataGulma table has data
        $count = DataGulma::count();
        $this->info("1. Total DataGulma records: {$count}");

        // Test 2: Check latest published
        $published = MapPublication::getLatestPublished();
        if ($published) {
            $this->info("2. Latest published: Import ID {$published->import_log_id}");
            $dataCount = DataGulma::where('import_log_id', $published->import_log_id)->count();
            $this->info("   Total records in publication: {$dataCount}");
        } else {
            $this->warn("2. No published data found");
        }

        // Test 3: Check CSV columns
        $sample = DataGulma::first();
        if ($sample) {
            $this->info("3. Sample CSV data:");
            $this->line("   PG: {$sample->pg}");
            $this->line("   FM: {$sample->fm}");
            $this->line("   Seksi: {$sample->seksi}");
            $this->line("   Neto: {$sample->neto}");
            $this->line("   Hasil: {$sample->hasil}");
            $this->line("   Umur: {$sample->umur}");
            $this->line("   TNM STS: {$sample->tnm_sts}");
            $this->line("   Aktivitas: {$sample->activitas}");
            $this->line("   Kategori: {$sample->kategori}");
            $this->line("   Tanggal: {$sample->tanggal}");
            $this->line("   TK/HA: {$sample->tk_ha}");
            $this->line("   Total TK: {$sample->total_tk}");
        } else {
            $this->warn("3. No sample data found");
        }

        $this->newLine();
        $this->info('✅ CSV API test complete');

        return Command::SUCCESS;
    }
}
