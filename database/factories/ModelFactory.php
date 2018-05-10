<?php

use Faker\Generator;
use Rogue\Models\Post;
use Rogue\Models\Tag;
use Rogue\Models\User;
use Rogue\Services\AWS;
use Rogue\Models\Signup;
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
        'url' => $url,
        'text' => $faker->sentence(),
        'source' => $faker->randomElement(['phoenix-oauth', 'phoenix-next']),
        'remote_addr' => $faker->ipv4,
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

// Tag Factory
$factory->define(Tag::class, function (Generator $faker) {
    $tagName = str_random(10);
    return [
        'tag_name' => $tagName,
        'tag_slug' => str_slug($tagName, '-')
    ];
});

$factory->defineAs(Tag::class, 'Hide In Gallery', function () use ($factory) {
    return array_merge($factory->raw(Tag::class), ['tag_name' => 'Hide In Gallery', 'tag_slug' => 'hide-in-gallery']);
});
