<?php

declare(strict_types=1);

// config for JakubOrava/ScaleoIoClient
return [
    /*
    |--------------------------------------------------------------------------
    | Scaleo API Key
    |--------------------------------------------------------------------------
    |
    | Your Scaleo API key. You can find this in your Scaleo account settings.
    |
    */
    'api_key' => env('SCALEO_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Scaleo Tracking URL
    |--------------------------------------------------------------------------
    |
    | Your Scaleo tracking URL (e.g., https://sandbox.scaletrk.com).
    | Each Scaleo account has its own tracking URL.
    |
    */
    'base_url' => env('SCALEO_BASE_URL', 'https://sandbox.scaletrk.com'),
];
