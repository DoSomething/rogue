<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    |
    | This file is a custom addition to Rogue for storing feature flags, so
    | features can be conditionally toggled on and off per environment.
    |
    */

    'customer_io' => env('DS_ENABLE_CUSTOMER_IO'),
    'gambit' => env('DS_ENABLE_GAMBIT_RELAY', false),
    'track_club_id' => env('DS_ENABLE_TRACK_CLUB_IDS', false),
];
