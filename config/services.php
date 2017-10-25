<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'northstar' => [
        'grant' => 'authorization_code',
        'url' => env('NORTHSTAR_URL'),
        'key' => storage_path('keys/public.key'),
        'authorization_code' => [
            'client_id' => env('NORTHSTAR_AUTH_ID'),
            'client_secret' => env('NORTHSTAR_AUTH_SECRET'),
            'scope' => ['user', 'role:staff', 'role:admin'],
            'redirect_uri' => '/login',
        ],
        'client_credentials' => [
            'client_id' => env('NORTHSTAR_CLIENT_ID'),
            'client_secret' => env('NORTHSTAR_CLIENT_SECRET'),
            'scope' => ['user', 'admin'],
        ],
    ],

    'phoenix' => [
        'uri' => env('PHOENIX_URI'),
        'version' => env('PHOENIX_API_VERSION'),
    ],

    'blink' => [
        'url' => env('BLINK_URL'),
        'user' => env('BLINK_USERNAME'),
        'password' => env('BLINK_PASSWORD'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => Rogue\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

];
