<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ViewLogs extends Command
{
    protected $signature = 'logs:view {lines=50}';
    protected $description = 'View latest log entries';

    public function handle()
    {
        $lines = (int)$this->argument('lines');
        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            $this->error('Log file not found: ' . $logFile);
            return;
        }

        $content = file_get_contents($logFile);
        $logLines = explode("\n", $content);
        
        // Get last N lines
        $lastLines = array_slice($logLines, -$lines);
        
        $this->info('Last ' . $lines . ' lines from laravel.log:');
        $this->line('==============================');
        
        foreach ($lastLines as $line) {
            if (!empty(trim($line))) {
                $this->line($line);
            }
        }

        return Command::SUCCESS;
    }
}
