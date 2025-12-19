<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class WilayahMapTest extends TestCase
{
    /**
     * Test coordinate transformation
     */
    public function testCoordinateTransformation()
    {
        // UTM Zone 48S coordinate dari Wil16.geojson
        $utmEasting = 524308.254454578389414;
        $utmNorthing = 9484369.360605746507645;
        
        // Import transformer
        include_once base_path('app/Utils/CoordinateTransformer.php');
        
        $result = \App\Utils\CoordinateTransformer::utm32748ToWgs84($utmEasting, $utmNorthing);
        
        // Verify output is LatLng format
        $this->assertIsArray($result);
        $this->assertArrayHasKey('lat', $result);
        $this->assertArrayHasKey('lng', $result);
        
        // Verify values are in reasonable range
        $this->assertGreaterThan(-10, $result['lat']);
        $this->assertLessThan(-5, $result['lat']);
        $this->assertGreaterThan(105, $result['lng']);
        $this->assertLessThan(110, $result['lng']);
        
        echo "\n✓ Coordinate Transformation Test PASSED\n";
        echo "  UTM: ({$utmEasting}, {$utmNorthing})\n";
        echo "  WGS84: ({$result['lat']}, {$result['lng']})\n";
    }
    
    /**
     * Test GeoJSON file exists
     */
    public function testGeoJsonFilesExist()
    {
        for ($i = 16; $i <= 23; $i++) {
            $file = storage_path("../data/Wil{$i}.geojson");
            $this->assertFileExists($file, "GeoJSON file Wil{$i}.geojson not found");
        }
        
        echo "\n✓ All GeoJSON Files Test PASSED\n";
        echo "  Found 8 wilayah files (Wil16-Wil23)\n";
    }
    
    /**
     * Test GeoJSON format validity
     */
    public function testGeoJsonValidity()
    {
        $file = storage_path("../data/Wil16.geojson");
        $content = file_get_contents($file);
        $geojson = json_decode($content, true);
        
        // Check structure
        $this->assertArrayHasKey('type', $geojson);
        $this->assertArrayHasKey('features', $geojson);
        $this->assertEquals('FeatureCollection', $geojson['type']);
        
        // Check CRS
        $this->assertArrayHasKey('crs', $geojson);
        $this->assertStringContainsString('32748', $geojson['crs']['properties']['name']);
        
        // Check features
        $this->assertGreaterThan(0, count($geojson['features']));
        
        echo "\n✓ GeoJSON Format Test PASSED\n";
        echo "  File: Wil16.geojson\n";
        echo "  CRS: EPSG:32748 (UTM Zone 48S)\n";
        echo "  Features: " . count($geojson['features']) . "\n";
    }
}

// Run tests
echo "\n" . str_repeat("=", 50) . "\n";
echo "WILAYAH MAP SYSTEM - TEST SUITE\n";
echo str_repeat("=", 50) . "\n";

$test = new WilayahMapTest();

try {
    $test->testCoordinateTransformation();
} catch (\Exception $e) {
    echo "\n✗ Coordinate Transformation Test FAILED: " . $e->getMessage() . "\n";
}

try {
    $test->testGeoJsonFilesExist();
} catch (\Exception $e) {
    echo "\n✗ GeoJSON Files Test FAILED: " . $e->getMessage() . "\n";
}

try {
    $test->testGeoJsonValidity();
} catch (\Exception $e) {
    echo "\n✗ GeoJSON Format Test FAILED: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "Test suite completed!\n";
echo str_repeat("=", 50) . "\n";
