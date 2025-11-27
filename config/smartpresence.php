<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Smart Presence Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the Smart Presence application.
    |
    */

    'attendance' => [
        // Threshold for facial recognition confidence (0.0 to 1.0)
        'confidence_threshold' => 0.8,

        // Time window (in minutes) for considering a check-in as on-time
        'on_time_window' => 15,

        // Time window (in minutes) for auto-checkout if user doesn't check out
        'auto_checkout_window' => 60,
    ],

    'ai' => [
        // Vector database configuration (if used)
        'vector_database' => [
            'enabled' => false,
            'driver' => 'pinecone', // pinecone, weaviate, etc.
            'api_key' => env('VECTOR_DB_API_KEY'),
        ],

        // Face recognition service configuration
        'face_recognition' => [
            'service' => 'internal', // internal, aws_rekognition, google_vision, etc.
            'min_confidence' => 80,
        ],
    ],

    'storage' => [
        // Path for storing face images
        'face_images' => 'faces',

        // Maximum file size for face images (in KB)
        'max_face_image_size' => 2048,
    ],

    'notifications' => [
        // Enable/disable various notifications
        'face_recognition_failed' => true,
        'low_confidence_alert' => true,
        'attendance_summary' => true,
    ],

    'school' => [
        // School location settings for geolocation validation
        'latitude' => -6.2088,
        'longitude' => 106.8456,
        'max_distance' => 100, // in meters
    ],
];