<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DataGulma;
use App\Models\ImportLog;
use App\Models\MapPublication;

class TestCsvParsing extends Command
{
    protected $signature = 'test:csv-parsing';
    protected $description = 'Test CSV parsing logic';

    public function handle()
    {
        $this->info('Testing CSV Parsing...');
        $this->line('==============================');

        $path = public_path('test_sample.csv');
        
        if (!file_exists($path)) {
            $this->error('Test CSV file not found');
            return;
        }

        $csv = array_map('str_getcsv', file($path));
        
        // Get and normalize headers
        $headers = array_shift($csv);
        $headers = array_map('strtolower', $headers);
        $headers = array_map('trim', $headers);
        
        $this->info('Headers found:');
        foreach ($headers as $idx => $header) {
            $this->line("  [$idx] $header");
        }

        // Test flexible getter
        $getField = function($fieldName) use ($headers, $csv) {
            $fieldLower = strtolower($fieldName);
            foreach ($headers as $idx => $key) {
                $keyNorm = str_replace(['_', ' ', '/'], '', strtolower($key));
                $fieldNorm = str_replace(['_', ' ', '/'], '', $fieldLower);
                if ($keyNorm === $fieldNorm) {
                    return function($row) use ($idx) {
                        return $row[$idx] ?? null;
                    };
                }
            }
            return null;
        };

        // Test parsing first row
        $this->newLine();
        $this->info('Parsing first data row:');
        
        if (!empty($csv)) {
            $firstRow = $csv[0];
            $data = array_combine($headers, $firstRow);
            $data = array_map('trim', $data);
            
            foreach ($data as $key => $val) {
                $this->line("  $key: '$val'");
            }
        }

        $this->newLine();
        $this->info('✅ CSV parsing test complete');

        return Command::SUCCESS;
    }
}
