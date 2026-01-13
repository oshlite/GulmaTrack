<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$log = DB::table('import_logs')->latest()->first();

if ($log) {
    echo "=== Import Log Details ===\n";
    echo "File: {$log->nama_file}\n";
    echo "Wilayah: {$log->wilayah_id}\n";
    echo "Total Records: {$log->jumlah_records}\n";
    echo "Berhasil: {$log->jumlah_berhasil}\n";
    echo "Gagal: {$log->jumlah_gagal}\n";
    echo "Status: {$log->status}\n";
    echo "\n=== Error Log ===\n";
    if ($log->error_log) {
        $errors = json_decode($log->error_log, true);
        if (is_array($errors)) {
            foreach ($errors as $i => $error) {
                echo ($i + 1) . ". $error\n";
                if ($i >= 9) {
                    echo "... dan " . (count($errors) - 10) . " error lainnya\n";
                    break;
                }
            }
        } else {
            echo $log->error_log . "\n";
        }
    } else {
        echo "Tidak ada error log\n";
    }
} else {
    echo "Tidak ada import log\n";
}
