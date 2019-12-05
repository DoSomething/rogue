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

    'pushToQuasar' => env('DS_ENABLE_PUSH_TO_QUASAR'),
];
