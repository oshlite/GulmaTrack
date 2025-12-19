<?php

namespace App\Utils;

/**
 * Coordinate transformation utility
 * Convert between UTM and WGS84 (LatLng)
 */
class CoordinateTransformer
{
    // WGS84 Parameters
    private const WGS84_A = 6378137.0;
    private const WGS84_B = 6356752.314245;
    private const WGS84_E2 = 0.00669438;
    
    // UTM Zone 48S Parameters
    private const ZONE = 48;
    private const FALSE_EASTING = 500000;
    private const FALSE_NORTHING = 10000000;
    private const SCALE_FACTOR = 0.9996;

    /**
     * Convert UTM (32748 - Zone 48S) to WGS84 (LatLng)
     * 
     * @param float $easting
     * @param float $northing
     * @return array ['lat' => float, 'lng' => float]
     */
    public static function utm32748ToWgs84($easting, $northing)
    {
        $x = $easting - self::FALSE_EASTING;
        $y = $northing - self::FALSE_NORTHING;

        $m = $y / self::SCALE_FACTOR;
        $mu = $m / (self::WGS84_A * (1 - self::WGS84_E2 / 4 - 3 * self::WGS84_E2 * self::WGS84_E2 / 64 - 5 * self::WGS84_E2 * self::WGS84_E2 * self::WGS84_E2 / 256));
        
        $p1 = $mu + (3 / 2) * 0.00675701 * sin(2 * $mu) - (27 / 32) * 0.00675701 * 0.00675701 * sin(4 * $mu);
        
        $p_lat = asin(sin($p1) / (1 + 0.00675701 * cos($p1) * cos($p1)));
        $p_lng = atan(tan($x / (self::WGS84_A * cos($p_lat) * self::SCALE_FACTOR)) / cos($p_lat));
        
        $lng = (self::ZONE * 6 - 180) + rad2deg($p_lng);
        $lat = rad2deg($p_lat);

        return [
            'lat' => $lat,
            'lng' => $lng
        ];
    }

    /**
     * Convert GeoJSON feature dari UTM ke WGS84
     * 
     * @param array $feature GeoJSON feature
     * @return array Converted feature
     */
    public static function convertFeatureToWgs84($feature)
    {
        if (!isset($feature['geometry'])) {
            return $feature;
        }

        $geometry = $feature['geometry'];
        
        if ($geometry['type'] === 'MultiPolygon') {
            $geometry['coordinates'] = self::convertMultiPolygonCoordinates($geometry['coordinates']);
        } elseif ($geometry['type'] === 'Polygon') {
            $geometry['coordinates'] = self::convertPolygonCoordinates($geometry['coordinates']);
        } elseif ($geometry['type'] === 'Point') {
            $geometry['coordinates'] = self::convertPointCoordinates($geometry['coordinates']);
        }

        $feature['geometry'] = $geometry;
        return $feature;
    }

    /**
     * Convert MultiPolygon coordinates
     */
    private static function convertMultiPolygonCoordinates($multiPolygon)
    {
        $result = [];
        foreach ($multiPolygon as $polygon) {
            $result[] = self::convertPolygonCoordinates($polygon);
        }
        return $result;
    }

    /**
     * Convert Polygon coordinates
     */
    private static function convertPolygonCoordinates($polygon)
    {
        $result = [];
        foreach ($polygon as $ring) {
            $result[] = self::convertRingCoordinates($ring);
        }
        return $result;
    }

    /**
     * Convert Ring (array of points) coordinates
     */
    private static function convertRingCoordinates($ring)
    {
        $result = [];
        foreach ($ring as $point) {
            $converted = self::utm32748ToWgs84($point[0], $point[1]);
            $result[] = [$converted['lng'], $converted['lat']];
        }
        return $result;
    }

    /**
     * Convert Point coordinates
     */
    private static function convertPointCoordinates($point)
    {
        $converted = self::utm32748ToWgs84($point[0], $point[1]);
        return [$converted['lng'], $converted['lat']];
    }

    /**
     * Convert entire GeoJSON FeatureCollection to WGS84
     * 
     * @param array $geojson
     * @return array
     */
    public static function convertGeoJsonToWgs84($geojson)
    {
        if (!isset($geojson['features']) || !is_array($geojson['features'])) {
            return $geojson;
        }

        $features = [];
        foreach ($geojson['features'] as $feature) {
            $features[] = self::convertFeatureToWgs84($feature);
        }

        $geojson['features'] = $features;
        
        // Update CRS to WGS84
        $geojson['crs'] = [
            'type' => 'name',
            'properties' => [
                'name' => 'urn:ogc:def:crs:EPSG::4326'
            ]
        ];

        return $geojson;
    }
}
