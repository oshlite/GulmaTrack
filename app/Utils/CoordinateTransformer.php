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
     * Lampung area is in UTM Zone 48S (EPSG:32748)
     * 
     * @param float $easting
     * @param float $northing
     * @return array ['lat' => float, 'lng' => float]
     */
    public static function utm32748ToWgs84($easting, $northing)
    {
        // UTM Zone 48S parameters
        $k0 = self::SCALE_FACTOR;
        $a = self::WGS84_A;
        $e = sqrt(self::WGS84_E2);
        $e2 = self::WGS84_E2;
        
        // Remove false easting and northing
        $x = $easting - self::FALSE_EASTING;
        $y = $northing - self::FALSE_NORTHING;
        
        // Calculate footpoint latitude
        $M = $y / $k0;
        $mu = $M / ($a * (1 - $e2/4 - 3*$e2*$e2/64 - 5*$e2*$e2*$e2/256));
        
        $e1 = (1 - sqrt(1-$e2)) / (1 + sqrt(1-$e2));
        
        $phi1 = $mu + (3*$e1/2 - 27*$e1*$e1*$e1/32) * sin(2*$mu)
                    + (21*$e1*$e1/16 - 55*$e1*$e1*$e1*$e1/32) * sin(4*$mu)
                    + (151*$e1*$e1*$e1/96) * sin(6*$mu)
                    + (1097*$e1*$e1*$e1*$e1/512) * sin(8*$mu);
        
        $C1 = $e2 * pow(cos($phi1), 2);
        $T1 = pow(tan($phi1), 2);
        $N1 = $a / sqrt(1 - $e2 * pow(sin($phi1), 2));
        $R1 = $a * (1 - $e2) / pow(1 - $e2 * pow(sin($phi1), 2), 1.5);
        $D = $x / ($N1 * $k0);
        
        $lat = $phi1 - ($N1 * tan($phi1) / $R1) * 
               ($D*$D/2 - (5 + 3*$T1 + 10*$C1 - 4*$C1*$C1 - 9*$e2) * $D*$D*$D*$D/24
               + (61 + 90*$T1 + 298*$C1 + 45*$T1*$T1 - 252*$e2 - 3*$C1*$C1) * pow($D, 6)/720);
        
        $lng = ($D - (1 + 2*$T1 + $C1) * $D*$D*$D/6
               + (5 - 2*$C1 + 28*$T1 - 3*$C1*$C1 + 8*$e2 + 24*$T1*$T1) * pow($D, 5)/120) / cos($phi1);
        
        // Convert to degrees and add central meridian
        $lat = rad2deg($lat);
        $lng = rad2deg($lng) + (self::ZONE * 6 - 183); // Central meridian for zone 48

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
