<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Selcom API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Selcom payment gateway integration.
    | These values should be set in your .env file.
    |
    */

    'api_key' => env('SELCOM_API_KEY'),

    'api_secret' => env('SELCOM_API_SECRET'),

    'vendor_id' => env('SELCOM_VENDOR_ID'),

    'base_url' => env('SELCOM_BASE_URL', 'https://api.selcom.co.tz'),

    /*
    |--------------------------------------------------------------------------
    | Selcom Environment
    |--------------------------------------------------------------------------
    |
    | Set to 'production' for live payments or 'sandbox' for testing.
    |
    */

    'environment' => env('SELCOM_ENVIRONMENT', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | Selcom Sandbox URLs
    |--------------------------------------------------------------------------
    |
    | URLs for sandbox/testing environment.
    |
    */

    'sandbox' => [
        'base_url' => 'https://api.selcom.co.tz',
    ],

    /*
    |--------------------------------------------------------------------------
    | Selcom Production URLs
    |--------------------------------------------------------------------------
    |
    | URLs for production/live environment.
    |
    */

    'production' => [
        'base_url' => 'https://api.selcom.co.tz',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | Default currency for transactions.
    |
    */

    'currency' => env('SELCOM_CURRENCY', 'TZS'),

    /*
    |--------------------------------------------------------------------------
    | Payment Types
    |--------------------------------------------------------------------------
    |
    | Supported payment types.
    |
    */

    'payment_types' => [
        'card' => 'Card Payment',
        'mobile' => 'Mobile Money',
        'lipa_kidogo' => 'Lipa Kidogo (Installments)',
    ],

    /*
    |--------------------------------------------------------------------------
    | Callback Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for payment callbacks.
    |
    */

    'callback' => [
        'timeout' => env('SELCOM_CALLBACK_TIMEOUT', 300), // 5 minutes
        'retries' => env('SELCOM_CALLBACK_RETRIES', 3),
    ],
];
