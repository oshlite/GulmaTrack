<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

// Bootstrap the application
$kernel->bootstrap();

echo "=== Testing WilayahController API Endpoints ===\n\n";

// Test 1: /api/wilayah/data with period parameter
echo "TEST 1: /api/wilayah/data?tahun=2030&bulan=3&minggu=3\n";
echo str_repeat('=', 70) . "\n";

$request = Illuminate\Http\Request::create('/api/wilayah/data', 'GET', [
    'tahun' => '2030',
    'bulan' => '3',
    'minggu' => '3'
]);

$app->instance('request', $request);
$controller = new App\Http\Controllers\WilayahController();

try {
    $response = $controller->getData($request);
    $data = json_decode($response->getContent(), true);
    
    // Find Wilayah 16
    $wil16 = null;
    foreach ($data['data'] as $wil) {
        if ($wil['wilayah'] == 16) {
            $wil16 = $wil;
            break;
        }
    }
    
    if ($wil16) {
        echo "✅ Found Wilayah 16 data:\n";
        echo "   import_log_id: " . ($data['import_log_id'] ?? 'N/A') . "\n";
        echo "   total_luas_netto: " . $wil16['total_luas_netto'] . " Ha\n";
        echo "   total_tk: " . $wil16['total_tk'] . " TK\n";
        echo "   Status counts:\n";
        echo "      Bersih: " . $wil16['status_counts']['bersih'] . "\n";
        echo "      Ringan: " . $wil16['status_counts']['ringan'] . "\n";
        echo "      Sedang: " . $wil16['status_counts']['sedang'] . "\n";
        echo "      Berat: " . $wil16['status_counts']['berat'] . "\n";
        
        // Verify against expected values
        echo "\n   Verification:\n";
        $expected_tk = 413.80;
        $expected_neto = 103.45;
        $expected_ringan = 14;
        
        $tk_match = abs($wil16['total_tk'] - $expected_tk) < 0.01;
        $neto_match = abs($wil16['total_luas_netto'] - $expected_neto) < 0.01;
        $ringan_match = $wil16['status_counts']['ringan'] == $expected_ringan;
        
        echo "      Total TK: " . ($tk_match ? "✅ MATCH" : "❌ MISMATCH (expected $expected_tk)") . "\n";
        echo "      Total Neto: " . ($neto_match ? "✅ MATCH" : "❌ MISMATCH (expected $expected_neto)") . "\n";
        echo "      Ringan count: " . ($ringan_match ? "✅ MATCH" : "❌ MISMATCH (expected $expected_ringan)") . "\n";
    } else {
        echo "❌ Wilayah 16 not found in response\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n\n";

// Test 2: /api/wilayah/stats/16 with period parameter
echo "TEST 2: /api/wilayah/stats/16?tahun=2030&bulan=3&minggu=3\n";
echo str_repeat('=', 70) . "\n";

$request2 = Illuminate\Http\Request::create('/api/wilayah/stats/16', 'GET', [
    'tahun' => '2030',
    'bulan' => '3',
    'minggu' => '3'
]);

$app->instance('request', $request2);

try {
    $response = $controller->getWilayahStats(16, $request2);
    $data = json_decode($response->getContent(), true);
    
    echo "✅ Response received:\n";
    echo "   wilayah_id: " . $data['wilayah_id'] . "\n";
    echo "   total_records: " . $data['total_records'] . "\n";
    echo "   bersih_count: " . $data['bersih_count'] . "\n";
    echo "   ringan_count: " . $data['ringan_count'] . "\n";
    echo "   sedang_count: " . $data['sedang_count'] . "\n";
    echo "   berat_count: " . $data['berat_count'] . "\n";
    echo "   total_tk: " . $data['total_tk'] . "\n";
    echo "   total_neto: " . $data['total_neto'] . "\n";
    echo "   import_log_id: " . ($data['import_log_id'] ?? 'N/A') . "\n";
    
    // Verify
    echo "\n   Verification:\n";
    $expected_tk = 413.80;
    $expected_neto = 103.45;
    $expected_ringan = 14;
    
    $tk_match = abs($data['total_tk'] - $expected_tk) < 0.01;
    $neto_match = abs($data['total_neto'] - $expected_neto) < 0.01;
    $ringan_match = $data['ringan_count'] == $expected_ringan;
    
    echo "      Total TK: " . ($tk_match ? "✅ MATCH" : "❌ MISMATCH (expected $expected_tk)") . "\n";
    echo "      Total Neto: " . ($neto_match ? "✅ MATCH" : "❌ MISMATCH (expected $expected_neto)") . "\n";
    echo "      Ringan count: " . ($ringan_match ? "✅ MATCH" : "❌ MISMATCH (expected $expected_ringan)") . "\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
