<?php

use Rogue\Models\Post;
use Rogue\Models\Signup;
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
        // Create 20 signups with one accepted post & some pending posts.
        factory(Signup::class, 20)->create()->each(function (Signup $signup) {
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
//
        // Create 10 signups with rejected posts, from troublemakers!
        factory(Signup::class, 10)->create()->each(function (Signup $signup) {
            $signup->posts()->save(factory(Post::class, 'rejected')->create([
                'signup_id' => $signup->id,
                'campaign_id' => $signup->campaign_id,
                'northstar_id' => $signup->northstar_id,
            ]));
        });

        // Create 100 signups with no posts yet.
        factory(Signup::class, 100)->create();
    }
}
