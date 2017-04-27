<?php


return [
    
    'env' => env('APP_ENV', 'local'),
    'key' => env('APP_KEY', 'temporary key'), //used for encryption purposes
    'storage_path' => __DIR__ . "/../storage",
    'app_base_url' => env("APP_BASE_URL", "http://localhost"),
    'api' => [
        'exchange_path' => '/api/auth/exchange/google',
        'base_url' => env("API_BASE_URL", "http://localhost"),
    ],
];
