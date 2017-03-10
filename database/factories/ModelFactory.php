<?php

use Faker\Generator;
use Rogue\Models\Post;
use Rogue\Models\User;
use Rogue\Models\Event;
use Rogue\Models\Photo;
use Rogue\Models\Signup;
use Rogue\Models\Reaction;
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

// User Event Factory
$factory->defineAs(Event::class, 'user', function (Generator $faker) {
    return [
        'northstar_id' => str_random(24),
        'submission_type' => 'user',
    ];
});

// Photo Factory
$factory->define(Photo::class, function (Generator $faker) {
    return [
        'file_url' => 'https://s3.amazonaws.com/ds-rogue-test/uploads/reportback-items/12-1484929292.jpeg',
        'edited_file_url' => null,
        'caption' => $this->faker->sentence(),
        'status' => 'pending',
        'source' => 'phoenix-web',
        'remote_addr' => '10.0.2.2',
    ];
});

// Post Factory
$factory->define(Post::class, function (Generator $faker) {
    return [
        'northstar_id' => str_random(24),
        'event_id' => function () {
            return factory(Event::class, 'user')->create()->id;
        },
        'signup_id' => function () {
            return factory(Signup::class)->create()->id;
        },
    ];
});

// Signup Factory
$factory->define(Signup::class, function (Generator $faker) {
    return [
        'campaign_id' => $this->faker->randomNumber(4),
        'campaign_run_id' => $this->faker->randomNumber(4),
        'quantity_pending' => $this->faker->randomNumber(4),
        'why_participated' => $this->faker->sentence(),
        'source' => 'phoenix-web',
        'event_id' => function () {
            return factory(Event::class, 'user')->create()->id;
        },
    ];
});

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
        'flagged'          => null,
        'flagged_reason'   => null,
        'promoted'         => null,
        'promoted_reason'  => null,
    ];
});

// Reaction Factory
$factory->define(Reaction::class, function (Generator $faker) {
    return [
        'northstar_id' => str_random(24),
        'reportback_item_id' => $faker->randomNumber(7),
    ];
});

// Base User Factory
$factory->define(User::class, function (Generator $faker) {
    return [
        'northstar_id' => str_random(24),
        'access_token' => str_random(1024),
        'access_token_expiration' => str_random(11),
        'refresh_token' => str_random(1024),
        'remember_token' => str_random(10),
        'role' => null,
    ];
});
