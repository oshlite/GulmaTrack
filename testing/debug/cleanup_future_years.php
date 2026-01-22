<?php

use Illuminate\Support\Facades\Artisan;
use App\Models\MapPublication;

// Run command: php artisan tinker < cleanup_future_years.php

$currentYear = now()->year;  // 2026
$maxYears = 3;  // Show last 3 years = 2024, 2025, 2026
$minYear = $currentYear - ($maxYears - 1);  // 2024

echo "=== CLEANUP FUTURE YEARS ===\n";
echo "Current Year: {$currentYear}\n";
echo "Valid Year Range: {$minYear} - {$currentYear}\n\n";

// Find and delete publications with tahun outside valid range
$invalidPublications = MapPublication::where(function($query) use ($minYear, $currentYear) {
    $query->where('tahun', '<', $minYear)
          ->orWhere('tahun', '>', $currentYear);
})->get();

if ($invalidPublications->count() > 0) {
    echo "Found " . $invalidPublications->count() . " publications to delete:\n";
    foreach ($invalidPublications as $pub) {
        echo "- ID: {$pub->id} | {$pub->tahun}/{$pub->bulan}/W{$pub->minggu} | Status: {$pub->status}\n";
    }
    
    $count = $invalidPublications->count();
    $deleted = MapPublication::where(function($query) use ($minYear, $currentYear) {
        $query->where('tahun', '<', $minYear)
              ->orWhere('tahun', '>', $currentYear);
    })->delete();
    
    echo "\n✅ Deleted {$deleted} invalid publications\n";
} else {
    echo "✅ No invalid publications found. All years are within valid range.\n";
}

// Show what remains
$remaining = MapPublication::where('status', 'published')
    ->select('tahun')
    ->distinct()
    ->orderBy('tahun', 'desc')
    ->get();

echo "\n=== REMAINING PUBLICATIONS ===\n";
foreach ($remaining as $pub) {
    echo "- Tahun: {$pub->tahun}\n";
}
?>
