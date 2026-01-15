<?php
/**
 * Debug Script - Check Database Data
 * Run: php tinker < database_check.php
 */

use App\Models\DataGulma;
use App\Models\MapPublication;

// Get latest publication
$pub = MapPublication::where('status', 'published')
    ->orderBy('published_at', 'desc')
    ->first();

if (!$pub) {
    echo "❌ No published data\n";
    exit;
}

echo "✅ Latest Publication: " . $pub->import_log_id . "\n\n";

// Get first 3 records from wilayah 16
$records = DataGulma::where('wilayah_id', 16)
    ->where('import_log_id', $pub->import_log_id)
    ->limit(3)
    ->get();

echo "Records found: " . $records->count() . "\n\n";

foreach ($records as $i => $record) {
    echo "Record $i:\n";
    echo "  seksi: " . $record->seksi . "\n";
    echo "  pg: " . $record->pg . "\n";
    echo "  fm: " . $record->fm . "\n";
    echo "  hasil: " . $record->hasil . "\n";
    echo "  umur: " . $record->umur . "\n";
    echo "  tnm_sts: " . $record->tnm_sts . "\n";
    echo "  activitas: " . $record->activitas . "\n";
    echo "  kategori: " . $record->kategori . "\n";
    echo "  tanggal: " . $record->tanggal . "\n";
    echo "  total_tk: " . $record->total_tk . "\n";
    echo "  tk_ha: " . $record->tk_ha . "\n";
    echo "---\n";
}
