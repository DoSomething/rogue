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

    'glide' => env('DS_ENABLE_GLIDE'),

    'blink' => env('DS_ENABLE_BLINK'),

];
