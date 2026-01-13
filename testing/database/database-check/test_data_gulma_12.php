<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Testing Data for Import 12 ===\n\n";

// Check import log
$import = \App\Models\ImportLog::find(12);
if ($import) {
    echo "✓ Import Log ID 12 found:\n";
    echo "  Status: {$import->status}\n";
    echo "  Wilayah: {$import->wilayah_id}\n";
    echo "  Berhasil: {$import->jumlah_berhasil}\n";
    echo "  Gagal: {$import->jumlah_gagal}\n";
    echo "  Tanggal: {$import->created_at}\n\n";
} else {
    echo "✗ Import Log ID 12 NOT FOUND\n\n";
    exit(1);
}

// Check data gulma
$totalRecords = \App\Models\DataGulma::where('import_log_id', 12)->count();
echo "Total DataGulma records for import 12: {$totalRecords}\n\n";

if ($totalRecords > 0) {
    // Get breakdown by wilayah
    $byWilayah = \App\Models\DataGulma::where('import_log_id', 12)
        ->groupBy('wilayah_id')
        ->selectRaw('wilayah_id, COUNT(*) as count')
        ->get();
    
    echo "Breakdown by Wilayah:\n";
    foreach ($byWilayah as $w) {
        echo "  Wilayah {$w->wilayah_id}: {$w->count} records\n";
    }
    
    echo "\nSample records:\n";
    $samples = \App\Models\DataGulma::where('import_log_id', 12)
        ->limit(5)
        ->get(['wilayah_id', 'seksi', 'kategori', 'pg', 'fm']);
    
    foreach ($samples as $s) {
        echo "  Wil {$s->wilayah_id}: seksi='{$s->seksi}', kategori='{$s->kategori}', pg='{$s->pg}', fm='{$s->fm}'\n";
    }
} else {
    echo "✗ NO DATA FOUND for import 12!\n";
}
