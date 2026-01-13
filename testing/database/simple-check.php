<?php

use App\Models\DataGulma;

$data = DataGulma::where('wilayah_id', 16)->get();
echo "Total DB records: " . $data->count() . "\n";
echo "Bersih: " . $data->where('kategori', 'Bersih')->count() . "\n";
echo "Ringan: " . $data->where('kategori', 'Ringan')->count() . "\n";
echo "TK sum: " . number_format($data->sum('tk_ha'), 2) . "\n";
echo "Neto sum: " . number_format($data->sum('neto'), 2) . "\n";
