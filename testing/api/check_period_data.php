<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Checking Publication for Period 2030/3/W3 ===\n\n";

$pub = App\Models\MapPublication::where('status', 'published')
    ->where('tahun', 2030)
    ->where('bulan', 3)
    ->where('minggu', 3)
    ->first();

if ($pub) {
    echo "✅ Publication found:\n";
    echo "   ID: {$pub->id}\n";
    echo "   import_log_id: {$pub->import_log_id}\n";
    echo "   published_at: {$pub->published_at}\n\n";
    
    echo "=== Checking Wilayah 16 Data ===\n\n";
    
    $records = App\Models\DataGulma::where('wilayah_id', 16)
        ->where('import_log_id', $pub->import_log_id)
        ->get();
    
    echo "Total records: " . $records->count() . "\n\n";
    
    if ($records->count() > 0) {
        $ringanCount = $records->where('kategori', 'Ringan')->count();
        $bersihCount = $records->where('kategori', 'Bersih')->count();
        $sedangCount = $records->where('kategori', 'Sedang')->count();
        $beratCount = $records->where('kategori', 'Berat')->count();
        
        $totalTk = $records->sum('total_tk');
        $totalNeto = $records->sum('neto');
        
        echo "Status Counts:\n";
        echo "  Bersih: {$bersihCount}\n";
        echo "  Ringan: {$ringanCount}\n";
        echo "  Sedang: {$sedangCount}\n";
        echo "  Berat: {$beratCount}\n\n";
        
        echo "Totals:\n";
        echo "  Total TK (TOTAL_TK): " . number_format($totalTk, 2) . "\n";
        echo "  Total Neto: " . number_format($totalNeto, 2) . "\n\n";
        
        echo "First 3 records:\n";
        foreach ($records->take(3) as $i => $rec) {
            echo "\n  Record " . ($i + 1) . ":\n";
            echo "    Seksi: {$rec->seksi}\n";
            echo "    Kategori: {$rec->kategori}\n";
            echo "    Total TK: {$rec->total_tk}\n";
            echo "    TK/HA: {$rec->tk_ha}\n";
            echo "    Neto: {$rec->neto}\n";
        }
    }
} else {
    echo "❌ No publication found for 2030/3/W3\n";
}

echo "\n=== Checking Latest Published ===\n\n";

$latest = App\Models\MapPublication::where('status', 'published')
    ->orderBy('published_at', 'desc')
    ->first();

if ($latest) {
    echo "Latest published period: {$latest->tahun}/{$latest->bulan}/W{$latest->minggu}\n";
    echo "import_log_id: {$latest->import_log_id}\n";
}
