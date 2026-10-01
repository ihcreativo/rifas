<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */
    'wompi' => [
        'public_key' => env('pub_prod_iqE4SfbZ7XDd6spBGJidMxgGci0Ajj76'),
        'private_key' => env('prv_prod_RNjCoFDT4UyBCVZo3VZYOsvXkyfVnD37'),
        'events_secret' => env('prod_events_8SdjJ3d69Jtbu5lmP5xF7l0ZvKxg6Zxf'),
        'integrity_secret' => env('prod_integrity_wfX1hbkkcA6kLSiSjQ73Tj66dEwHuvHb'),
        'api_url' => env('WOMPI_API_URL', 'https://sandbox.wompi.co/v1'),
    ],


    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

];
