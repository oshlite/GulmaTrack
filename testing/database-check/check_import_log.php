<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== IMPORT_LOGS TABLE STRUCTURE ===\n\n";

$columns = DB::select("
    SELECT column_name, data_type, is_nullable 
    FROM information_schema.columns 
    WHERE table_name = 'import_logs' 
    ORDER BY ordinal_position
");

foreach ($columns as $col) {
    echo sprintf("%-20s %-15s %s\n", 
        $col->column_name, 
        $col->data_type,
        $col->is_nullable === 'YES' ? 'NULL' : 'NOT NULL'
    );
}

echo "\n=== SAMPLE IMPORT_LOGS DATA ===\n\n";
$logs = DB::table('import_logs')
    ->orderBy('id', 'desc')
    ->limit(5)
    ->get(['id', 'tahun', 'bulan', 'minggu', 'wilayah_id', 'status', 'created_at']);

foreach ($logs as $log) {
    echo sprintf(
        "ID: %d | Tahun: %s | Bulan: %s | Minggu: %s | Wilayah ID: %s | Status: %s | Created: %s\n",
        $log->id,
        $log->tahun ?? 'NULL',
        $log->bulan ?? 'NULL',
        $log->minggu ?? 'NULL',
        $log->wilayah_id ?? 'NULL',
        $log->status,
        $log->created_at ?? 'NULL'
    );
}
