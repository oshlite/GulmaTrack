<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "===== MAP PUBLICATIONS =====\n";
$pubs = \App\Models\MapPublication::where('status', 'published')
    ->orderBy('tahun', 'desc')
    ->orderBy('bulan', 'desc')
    ->orderBy('minggu', 'desc')
    ->get(['id', 'tahun', 'bulan', 'minggu', 'import_log_id', 'status']);

foreach($pubs as $pub){
    echo "ID: {$pub->id} | {$pub->tahun}/{$pub->bulan}/W{$pub->minggu} | import_log: {$pub->import_log_id}\n";
}

echo "\n===== CHECKING 2030/3/W3 =====\n";
$target = \App\Models\MapPublication::where('tahun', 2030)
    ->where('bulan', 3)
    ->where('minggu', 3)
    ->where('status', 'published')
    ->first();

if($target){
    echo "✅ FOUND: ID {$target->id}, import_log_id: {$target->import_log_id}\n";
}else{
    echo "❌ NOT FOUND in published status\n";
    
    // Check if exists with any status
    $any = \App\Models\MapPublication::where('tahun', 2030)
        ->where('bulan', 3)
        ->where('minggu', 3)
        ->first();
    
    if($any){
        echo "⚠️ EXISTS but status is: {$any->status}\n";
    }
}

echo "\n===== IMPORT LOGS =====\n";
$logs = \App\Models\ImportLog::whereIn('id', [36, 40])->get(['id', 'filename', 'total_records']);
foreach($logs as $log){
    echo "ID: {$log->id} | {$log->filename} | {$log->total_records} records\n";
}
