<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeoLocationService extends Service
{
    /**
     * Validate if the given coordinates are within the school boundaries.
     *
     * @param float $latitude
     * @param float $longitude
     * @return bool
     */
    public function isWithinSchoolBoundaries($latitude, $longitude)
    {
        // In a real application, you would define school boundaries
        // and check if the given coordinates fall within those boundaries
        
        // For demonstration purposes, we'll use a simple radius check
        // around a central point (e.g., school location)
        
        $schoolLatitude = config('smartpresence.school.latitude', 0);
        $schoolLongitude = config('smartpresence.school.longitude', 0);
        $maxDistance = config('smartpresence.school.max_distance', 100); // in meters
        
        $distance = $this->calculateDistance(
            $schoolLatitude, 
            $schoolLongitude, 
            $latitude, 
            $longitude
        );
        
        return $distance <= $maxDistance;
    }

    /**
     * Calculate the distance between two points using the Haversine formula.
     *
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @return float Distance in meters
     */
    public function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Earth's radius in meters
        
        $lat1Rad = deg2rad($lat1);
        $lon1Rad = deg2rad($lon1);
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lon2);
        
        $latDelta = $lat2Rad - $lat1Rad;
        $lonDelta = $lon2Rad - $lon1Rad;
        
        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }

    /**
     * Get address information from coordinates.
     *
     * @param float $latitude
     * @param float $longitude
     * @return array|null
     */
    public function reverseGeocode($latitude, $longitude)
    {
        // In a real application, you would use a geocoding service
        // like Google Maps API or OpenStreetMap API
        
        // For demonstration, we'll return mock data
        return [
            'address' => '123 School Street',
            'city' => 'Education City',
            'state' => 'Learningshire',
            'country' => 'Knowledge Republic',
            'postal_code' => '12345',
        ];
    }

    /**
     * Validate coordinates format.
     *
     * @param float $latitude
     * @param float $longitude
     * @return bool
     */
    public function validateCoordinates($latitude, $longitude)
    {
        return is_numeric($latitude) && 
               is_numeric($longitude) && 
               $latitude >= -90 && 
               $latitude <= 90 && 
               $longitude >= -180 && 
               $longitude <= 180;
    }
}