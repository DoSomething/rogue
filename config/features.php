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

    'blink' => env('DS_ENABLE_BLINK'),

    'v3QuantitySupport' => env('DS_ENABLE_V3_QUANTITY_SUPPORT'),

    'pushToQuasar' => env('DS_ENABLE_PUSH_TO_QUASAR'),
];
