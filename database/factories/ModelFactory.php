<?php

use Faker\Generator;
use Rogue\Models\Reportback;


/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

// Reportback Factory
$factory->define(Reportback::class, function (Generator $faker) {
    return [
        'northstar_id'     => str_random(24),
        'drupal_id'        => $faker->randomNumber(8),
        'campaign_id'      => $faker->randomNumber(4),
        'campaign_run_id'  => $faker->randomNumber(4),
        'quantity'         => $faker->numberBetween(10, 1000),
        'why_participated' => $faker->paragraph(3),
        'num_participants' => $faker->optional(0.1)->numberBetween(2, 20),
        'flagged'          => NULL,
        'flagged_reason'   => NULL,
        'promoted'         => NULL,
        'promoted_reason'  => NULL,
    ];
});
