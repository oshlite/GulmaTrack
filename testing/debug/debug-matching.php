<?php

use App\Models\DataGulma;
use App\Models\MapPublication;

// Get latest published
$latest = MapPublication::where('status', 'published')->orderBy('published_at', 'desc')->first();

if ($latest && $latest->import_log_id) {
    $dbData = DataGulma::where('wilayah_id', 16)
        ->where('import_log_id', $latest->import_log_id)
        ->get();
    
    echo "Database records: " . $dbData->count() . "\n";
    echo "Bersih in DB: " . $dbData->where('kategori', 'Bersih')->count() . "\n";
    
    // Load GeoJSON
    $filePath = base_path() . '/datala/Wil16.geojson';
    $geojson = json_decode(file_get_contents($filePath), true);
    
    echo "\nGeoJSON features: " . count($geojson['features'] ?? []) . "\n";
    
    // Try to match
    $matched = 0;
    $bersihMatched = 0;
    $matchedBySeksi = [];
    
    foreach($dbData as $record) {
        $seksi = strtolower(trim($record->seksi));
        
        // Try to find in GeoJSON
        foreach($geojson['features'] ?? [] as $feature) {
            $lokasi = $feature['properties']['Lokasi'] 
                ?? $feature['properties']['SEKSI'] 
                ?? $feature['properties']['Seksi'] 
                ?? null;
            
            if ($lokasi && strtolower(trim($lokasi)) === $seksi) {
                $matched++;
                if ($record->kategori === 'Bersih') {
                    $bersihMatched++;
                }
                if (!isset($matchedBySeksi[$seksi])) {
                    $matchedBySeksi[$seksi] = [];
                }
                $matchedBySeksi[$seksi][] = $record->kategori;
                break;
            }
        }
    }
    
    echo "\nMatching results:\n";
    echo "  DB records matched to GeoJSON: $matched / " . $dbData->count() . "\n";
    echo "  Bersih records matched: $bersihMatched\n";
    echo "  Unmatched DB records: " . ($dbData->count() - $matched) . "\n";
    
    // Show unmatched
    $unmatchedSeksi = [];
    foreach($dbData as $record) {
        $seksi = strtolower(trim($record->seksi));
        if (!isset($matchedBySeksi[$seksi])) {
            if (!isset($unmatchedSeksi[$seksi])) {
                $unmatchedSeksi[$seksi] = 0;
            }
            $unmatchedSeksi[$seksi]++;
        }
    }
    
    if (count($unmatchedSeksi) > 0) {
        echo "\nUnmatched SEKSI (not in GeoJSON):\n";
        foreach($unmatchedSeksi as $seksi => $count) {
            echo "  $seksi: $count record(s)\n";
        }
    }
}
