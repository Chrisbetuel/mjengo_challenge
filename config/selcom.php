<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Selcom API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Selcom payment gateway integration.
    | Get these credentials from your Selcom dashboard.
    |
    */

    'api_key' => 'TILL61224964-df0113d1e78347e2bb40d17592c47387',

    'api_secret' => '05a99d-ef40c7-46359a-76a9ad-5438e9-5d',

    'vendor_id' => 'TILL61224964',

    'environment' => 'sandbox',

    /*
    |--------------------------------------------------------------------------
    | Selcom API Endpoints
    |--------------------------------------------------------------------------
    */

    'endpoints' => [
        'sandbox' => [
            'base_url' => 'https://apigw.selcommobile.com/v1',
            'checkout' => 'https://apigw.selcommobile.com/v1/checkout/create-order-minimal',
            'status' => 'https://apigw.selcommobile.com/v1/checkout/order-status',
            'lipa_kidogo' => 'https://apigw.selcommobile.com/v1/checkout/create-order-lipa-kidogo',
        ],
        'production' => [
            'base_url' => 'https://apigw.selcommobile.com/v1',
            'checkout' => 'https://apigw.selcommobile.com/v1/checkout/create-order-minimal',
            'status' => 'https://apigw.selcommobile.com/v1/checkout/order-status',
            'lipa_kidogo' => 'https://apigw.selcommobile.com/v1/checkout/create-order-lipa-kidogo',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Settings
    |--------------------------------------------------------------------------
    */

    'currency' => 'TZS',

    'callback_url' => env('APP_URL') . '/payments/callback',

    'timeout' => 30, // seconds

    /*
    |--------------------------------------------------------------------------
    | Lipa Kidogo Settings
    |--------------------------------------------------------------------------
    */

    'lipa_kidogo' => [
        'min_installments' => 2,
        'max_installments' => 12,
        'default_installments' => 3,
    ],
];
