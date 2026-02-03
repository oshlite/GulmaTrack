<?php
require_once 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

// Prepare and call
$kernel->handle($input = new \Symfony\Component\Console\Input\StringInput('tinker'), new \Symfony\Component\Console\Output\BufferedOutput());

// Direct test
$records = \App\Models\DataGulma::where('import_log_id', 55)->where('wilayah_id', 16)->limit(3)->get();

echo "Total records for import 55, wilayah 16: " . $records->count() . "\n\n";

if ($records->count() > 0) {
    echo "SAMPLE RECORDS:\n";
    foreach ($records as $rec) {
        echo "---\n";
        echo "ID: {$rec->id}\n";
        echo "seksi: {$rec->seksi}\n";
        echo "pg: {$rec->pg}\n";
        echo "fm: {$rec->fm}\n";
        echo "total_tk: {$rec->total_tk}\n";
        echo "kategori: {$rec->kategori}\n";
        echo "hasil: {$rec->hasil}\n";
    }
} else {
    echo "NO RECORDS FOUND!\n";
}
