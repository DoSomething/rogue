<?php

use Faker\Generator;
use Rogue\Models\Post;
use Rogue\Models\User;
use Rogue\Types\Cause;
use Rogue\Models\Action;
use Rogue\Models\Signup;
use Rogue\Models\Campaign;
use Rogue\Models\Reaction;
use Rogue\Types\ActionType;
use Rogue\Types\TimeCommitment;
use Rogue\Services\ImageStorage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Here you may define all of your model factories. Model factories give
 * you a convenient way to create models for testing and seeding your
 * database. Just tell the factory how a default model should look.
 *
 * @var Illuminate\Database\Eloquent\Factory $factory
 * @see https://laravel.com/docs/5.5/database-testing#writing-factories
 */

// Post Factory
$factory->define(Post::class, function (Generator $faker) {
    $faker->addProvider(new FakerNorthstarId($faker));

    $uploadPath = $faker->file(storage_path('fixtures'));
    $upload = new UploadedFile($uploadPath, basename($uploadPath), 'image/jpeg');
    $url = app(ImageStorage::class)->put($faker->unique()->randomNumber(5), $upload);

    return [
        'campaign_id' => function () {
            return factory(Campaign::class)->create()->id;
        },
        'signup_id' => function (array $attributes) {
            // If a 'signup_id' is not provided, create one for the same Campaign & Northstar ID.
            return factory(Signup::class)->create([
                'campaign_id' => $attributes['campaign_id'],
                'northstar_id' => $attributes['northstar_id'],
            ])->id;
        },
        'action_id' => function (array $attributes) {
            return factory(Action::class)->create([
                'campaign_id' => $attributes['campaign_id'],
            ])->id;
        },
        'northstar_id' => $this->faker->northstar_id,
        'url' => $url,
        'text' => $faker->sentence(),
        'location' => 'US-'.$faker->stateAbbr(),
        'source' => 'phpunit',
        'status' => 'pending',
        'quantity' => $faker->randomNumber(2),
    ];
});

// @TODO: These should all extend a photo-less "base" post, instead.
$factory->defineAs(Post::class, 'text', function (Generator $faker) {
    $faker->addProvider(new FakerNorthstarId($faker));

    return [
        'type' => 'text',
        'campaign_id' => function () {
            return factory(Campaign::class)->create()->id;
        },
        'signup_id' => function (array $attributes) {
            // If a 'signup_id' is not provided, create one for the same Campaign & Northstar ID.
            return factory(Signup::class)->create([
                'campaign_id' => $attributes['campaign_id'],
                'northstar_id' => $attributes['northstar_id'],
            ])->id;
        },
        'action_id' => function (array $attributes) {
            return factory(Action::class)->create([
                'campaign_id' => $attributes['campaign_id'],
            ])->id;
        },
        'northstar_id' => $this->faker->northstar_id,
        'text' => $faker->sentence(),
        'location' => 'US-'.$faker->stateAbbr(),
        'source' => 'phpunit',
        'status' => 'pending',
    ];
});

$factory->defineAs(Post::class, 'accepted', function () use ($factory) {
    return array_merge($factory->raw(Post::class), ['status' => 'accepted']);
});

$factory->defineAs(Post::class, 'rejected', function () use ($factory) {
    return array_merge($factory->raw(Post::class), ['status' => 'rejected']);
});

// Signup Factory
$factory->define(Signup::class, function (Generator $faker) {
    $faker->addProvider(new FakerNorthstarId($faker));

    return [
        'northstar_id' => $faker->northstar_id,
        'campaign_id' => function () {
            return factory(Campaign::class)->create()->id;
        },
        'why_participated' => $faker->sentence(),
        'source' => 'phpunit',
        'details' => $faker->randomElement([null, 'fun-affiliate-stuff', 'i-say-the-tails']),
    ];
});

// Reaction Factory
$factory->define(Reaction::class, function (Generator $faker) {
    $faker->addProvider(new FakerNorthstarId($faker));

    return [
        'northstar_id' => $faker->northstar_id,
        'post_id' => $faker->randomNumber(7),
    ];
});

// Base User Factory
$factory->define(User::class, function (Generator $faker) {
    $faker->addProvider(new FakerNorthstarId($faker));

    return [
        'northstar_id' => $faker->northstar_id,
        'access_token' => str_random(1024),
        'access_token_expiration' => str_random(11),
        'refresh_token' => str_random(1024),
        'remember_token' => str_random(10),
        'role' => 'user',
    ];
});

$factory->defineAs(User::class, 'admin', function () use ($factory) {
    return array_merge($factory->raw(User::class), ['role' => 'admin']);
});

// Campaign Factory
$factory->define(Campaign::class, function (Generator $faker) {
    return [
        'internal_title' => title_case($faker->unique()->catchPhrase),
        'cause' => $faker->randomElements(array_keys(Cause::all()), rand(1, 5)),
        'impact_doc' => 'https://www.google.com/',
        // By default, we create an "open campaign".
        'start_date' => $faker->dateTimeBetween('-6 months', 'now'),
        'end_date' => $faker->dateTimeBetween('+1 months', '+6 months'),
    ];
});

$factory->defineAs(Campaign::class, 'closed', function () use ($factory) {
    return array_merge($factory->raw(Campaign::class), [
        'start_date' => $faker->dateTimeBetween('-12 months', '-6 months'),
        'end_date' => $faker->dateTimeBetween('-3 months', 'now'),
    ]);
});

// Action Factory
$factory->define(Action::class, function (Generator $faker) {
    return [
        'name' => $faker->randomElement([
            'default', 'action-1', 'action-page', 'sms', 'august-2018-turbovote',
            'december-2018-turbovote', 'july-2018-turbovote', 'june-2018-turbovote',
            'may-2018-rockthevote', 'november-2018-turbovote',
        ]),
        'campaign_id' => factory(Campaign::class)->create()->id,
        'post_type' => 'photo',
        'action_type' => $this->faker->randomElement(ActionType::all()),
        'time_commitment' => $this->faker->randomElement(TimeCommitment::all()),
        'reportback' => true,
        'civic_action' => true,
        'scholarship_entry' => true,
        'anonymous' => false,
        'noun' => 'things',
        'verb' => 'done',
    ];
});
