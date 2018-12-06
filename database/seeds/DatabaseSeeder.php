<?php

use Rogue\Models\Post;
use Rogue\Models\Signup;
use Rogue\Models\Campaign;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 10 campaigns with signups & posts.
        factory(Campaign::class, 10)->create()->each(function (Campaign $campaign) {
            // Create 10-20 signups with one accepted post & some pending posts.
            factory(Signup::class, rand(10, 20))->create(['campaign_id' => $campaign->id])
                ->each(function (Signup $signup) {
                    $signup->posts()->save(factory(Post::class, 'accepted')->create([
                        'signup_id' => $signup->id,
                        'campaign_id' => $signup->campaign_id,
                        'northstar_id' => $signup->northstar_id,
                    ]));

                    $signup->posts()->saveMany(factory(Post::class, rand(2, 4))->create([
                        'signup_id' => $signup->id,
                        'campaign_id' => $signup->campaign_id,
                        'northstar_id' => $signup->northstar_id,
                    ]));
                });

            // Create 5-10 signups with only accepted posts, from lil' angels!
            factory(Signup::class, rand(10, 20))->create(['campaign_id' => $campaign->id])
                ->each(function (Signup $signup) {
                    $signup->posts()->save(factory(Post::class, 'accepted')->create([
                        'signup_id' => $signup->id,
                        'campaign_id' => $signup->campaign_id,
                        'northstar_id' => $signup->northstar_id,
                    ]));
                });

            // Create 5-10 signups with rejected posts, from troublemakers!
            factory(Signup::class, rand(10, 20))->create(['campaign_id' => $campaign->id])
                ->each(function (Signup $signup) {
                    $signup->posts()->save(factory(Post::class, 'rejected')->create([
                        'signup_id' => $signup->id,
                        'campaign_id' => $signup->campaign_id,
                        'northstar_id' => $signup->northstar_id,
                    ]));
                });

            // Create 100 signups with no posts yet.
            factory(Signup::class, 100)->create(['campaign_id' => $campaign->id]);
        });

        // And two campaigns with no activity yet.
        factory(Campaign::class, 2)->create();
    }
}
