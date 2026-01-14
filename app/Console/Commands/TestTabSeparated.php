<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DataGulma;
use App\Models\ImportLog;
use App\Models\MapPublication;

class TestTabSeparated extends Command
{
    protected $signature = 'test:tab-separated';
    protected $description = 'Test tab-separated CSV parsing';

    public function handle()
    {
        $this->info('Testing Tab-Separated CSV...');
        $this->line('==============================');

        $path = public_path('test_tab_separated.csv');
        
        if (!file_exists($path)) {
            $this->error('Test file not found');
            return;
        }

        // Read and detect delimiter
        $fileContent = file_get_contents($path);
        $firstLine = strtok($fileContent, "\n");
        $delimiter = (strpos($firstLine, "\t") !== false) ? "\t" : ",";
        
        $this->info('Delimiter detected: ' . ($delimiter === "\t" ? 'TAB' : 'COMMA'));
        $this->info('First line: ' . substr($firstLine, 0, 80));

        // Parse
        $csv = [];
        $lines = explode("\n", $fileContent);
        foreach ($lines as $line) {
            if (!empty(trim($line))) {
                $csv[] = str_getcsv($line, $delimiter);
            }
        }

        $this->info('Total lines parsed: ' . count($csv));

        // Get headers
        $headers = array_shift($csv);
        $headers = array_map('strtolower', $headers);
        $headers = array_map('trim', $headers);
        
        $this->info('Headers found:');
        foreach ($headers as $idx => $h) {
            $this->line("  [$idx] '{$h}'");
        }

        // Parse first row
        if (!empty($csv)) {
            $this->newLine();
            $this->info('First data row:');
            $data = array_combine($headers, $csv[0]);
            foreach ($data as $key => $val) {
                $this->line("  $key: '$val'");
            }
        }

        $this->newLine();
        $this->info('✅ Tab-separated parsing test complete');

        return Command::SUCCESS;
    }
}
