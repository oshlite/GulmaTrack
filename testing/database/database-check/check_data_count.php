<?php
// Quick check script untuk verify data di database
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

try {
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    $dataGulmaCount = \App\Models\DataGulma::count();
    $importLogCount = \App\Models\ImportLog::count();
    
    echo "=== Database Status ===\n";
    echo "Data Gulma Records: " . $dataGulmaCount . "\n";
    echo "Import Logs: " . $importLogCount . "\n";
    
    if ($importLogCount > 0) {
        $lastImport = \App\Models\ImportLog::latest()->first();
        echo "\n=== Latest Import ===\n";
        echo "ID: " . $lastImport->id . "\n";
        echo "File: " . $lastImport->nama_file . "\n";
        echo "Status: " . $lastImport->status . "\n";
        echo "Records: " . $lastImport->jumlah_records . "\n";
        echo "Wilayah: " . $lastImport->wilayah_id . "\n";
        
        if ($lastImport->status === 'success') {
            $relatedData = \App\Models\DataGulma::where('import_log_id', $lastImport->id)->limit(3)->get();
            echo "\nSample data from this import:\n";
            foreach ($relatedData as $data) {
                echo "  - Wilayah: " . $data->wilayah_id . 
                     ", Seksi: " . $data->seksi . 
                     ", Kategori: " . $data->kategori . "\n";
            }
        }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
?>
