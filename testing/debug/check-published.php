<?php

use App\Models\DataGulma;
use App\Models\MapPublication;

// Get latest published
$latest = MapPublication::where('status', 'published')->orderBy('published_at', 'desc')->first();

if ($latest) {
    echo "Latest published:\n";
    echo "  ID: " . $latest->id . "\n";
    echo "  Import Log ID: " . $latest->import_log_id . "\n";
    echo "  Published at: " . $latest->published_at . "\n";
    
    // Get data for this import
    $data = DataGulma::where('wilayah_id', 16)
        ->where('import_log_id', $latest->import_log_id)
        ->get();
    
    echo "\nData for Wilayah 16 in this import:\n";
    echo "  Total: " . $data->count() . "\n";
    echo "  Bersih: " . $data->where('kategori', 'Bersih')->count() . "\n";
    echo "  Ringan: " . $data->where('kategori', 'Ringan')->count() . "\n";
    echo "  TK sum: " . number_format($data->sum('tk_ha'), 2) . "\n";
    echo "  Neto sum: " . number_format($data->sum('neto'), 2) . "\n";
} else {
    echo "No published data found\n";
}
