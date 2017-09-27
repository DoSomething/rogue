<?php

use Faker\Generator;
use Rogue\Models\Post;
use Rogue\Models\User;
use Rogue\Services\AWS;
use Rogue\Models\Signup;
use Rogue\Models\Reaction;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

// Post Factory
$factory->define(Post::class, function (Generator $faker) {
    $uploadPath = $faker->file(storage_path('fixtures'));
    $upload = new UploadedFile($uploadPath, basename($uploadPath), 'image/jpeg');
    $url = app(AWS::class)->storeImage($upload, $faker->unique()->randomNumber(5));

    return [
        'url' => $url,
        'caption' => $faker->sentence(),
        'source' => $faker->randomElement(['phoenix-oauth', 'phoenix-next']),
        'remote_addr' => $faker->ipv4,
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
    $faker->addProvider(new FakerCampaignId($faker));
    $details_options = [null, null, 'fun-affiliate-stuff', 'i-say-the-tails'];

    return [
        'northstar_id' => $faker->northstar_id,
        'campaign_id' => $faker->campaign_id,
        'campaign_run_id' => $faker->randomNumber(4),
        'quantity_pending' => $faker->randomNumber(4),
        'why_participated' => $faker->sentence(),
        'source' => 'phoenix-web',
        'details' => $details_options[rand(0,3)],
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
