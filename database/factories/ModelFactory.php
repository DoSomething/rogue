<?php

use Faker\Generator;
use Rogue\Models\Post;
use Rogue\Models\User;
use Rogue\Services\AWS;
use Rogue\Models\Signup;
use Rogue\Models\Campaign;
use Rogue\Models\Reaction;
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
    $uploadPath = $faker->file(storage_path('fixtures'));
    $upload = new UploadedFile($uploadPath, basename($uploadPath), 'image/jpeg');
    $url = app(AWS::class)->storeImage($upload, $faker->unique()->randomNumber(5));

    return [
        'signup_id' => function () {
            return factory(Signup::class)->create()->id;
        },
        'northstar_id' => $this->faker->northstar_id,
        'url' => $url,
        'text' => $faker->sentence(),
        'source' => $faker->randomElement(['phoenix-oauth', 'phoenix-next']),
        'status' => 'pending',
        'quantity' => $faker->randomNumber(2),
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
    $faker->addProvider(new FakerCampaignId($faker));

    return [
        'northstar_id' => $faker->northstar_id,
        'campaign_id' => $faker->campaign_id,
        'campaign_run_id' => $faker->randomNumber(4),
        'why_participated' => $faker->sentence(),
        'source' => 'phoenix-web',
        'details' => $faker->randomElement([null, 'fun-affiliate-stuff', 'i-say-the-tails']),
    ];
});

$factory->state(Signup::class, 'contentful', function (Generator $faker) {
    $faker->addProvider(new FakerContentfulCampaignId($faker));

    return [
        'campaign_id' => $faker->contentful_id,
        'campaign_run_id' => null,
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
    $start_date = $this->faker->date($format = 'm/d/Y');
    $causes = [
        'Animals',
        'Bullying',
        'Disasters',
        'Discrimination',
        'Education',
        'Environment',
        'Homelessness',
        'Mental Health',
        'Physical Health',
        'Poverty',
        'Relationships',
        'Sex',
        'Violence',
    ];

    return [
        'id' => $faker->numberBetween($min = 9000, $max = 20000),
        'internal_title' => $faker->sentence(),
        'cause' => $causes[array_rand($causes)],
        'impact_doc' => 'https://www.google.com/',
        'start_date' => $start_date,
        // Make sure the end date is after the start date
        'end_date' => date('m/d/Y', strtotime("+3 months", strtotime($start_date))),
    ];
});
