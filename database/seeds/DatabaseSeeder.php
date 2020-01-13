<?php

use Rogue\Models\Post;
use Rogue\Models\Action;
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
            $photoAction = factory(Action::class)->create([
                'post_type' => 'photo',
                'campaign_id' => $campaign->id,
            ]);
            $textAction = factory(Action::class)->create([
                'post_type' => 'text',
                'campaign_id' => $campaign->id,
            ]);

            // Create 10-20 signups with one accepted photo post & some pending photo and text posts.
            factory(Signup::class, rand(10, 20))->create(['campaign_id' => $campaign->id])
                ->each(function (Signup $signup) use ($photoAction, $textAction) {
                    $signup->posts()->save(factory(Post::class, 'photo-accepted')->create([
                        'action_id' => $photoAction->id,
                        'signup_id' => $signup->id,
                        'campaign_id' => $signup->campaign_id,
                        'northstar_id' => $signup->northstar_id,
                    ]));

                    $signup->posts()->saveMany(factory(Post::class, 'photo-pending', rand(2, 4))->create([
                        'action_id' => $photoAction->id,
                        'signup_id' => $signup->id,
                        'campaign_id' => $signup->campaign_id,
                        'northstar_id' => $signup->northstar_id,
                    ]));

                    $signup->posts()->saveMany(factory(Post::class, 'text-pending', rand(2, 4))->create([
                        'action_id' => $textAction->id,
                        'signup_id' => $signup->id,
                        'campaign_id' => $signup->campaign_id,
                        'northstar_id' => $signup->northstar_id,
                    ]));
                });

            // Create 5-10 signups with only accepted posts, from lil' angels!
            factory(Signup::class, rand(10, 20))->create(['campaign_id' => $campaign->id])
                ->each(function (Signup $signup) use ($photoAction, $textAction) {
                    $signup->posts()->save(factory(Post::class, 'photo-accepted')->create([
                        'action_id' => $photoAction->id,
                        'signup_id' => $signup->id,
                        'campaign_id' => $signup->campaign_id,
                        'northstar_id' => $signup->northstar_id,
                    ]));

                    $signup->posts()->saveMany(factory(Post::class, 'text-accepted', rand(2, 4))->create([
                        'action_id' => $textAction->id,
                        'signup_id' => $signup->id,
                        'campaign_id' => $signup->campaign_id,
                        'northstar_id' => $signup->northstar_id,
                    ]));
                });

            // Create 5-10 signups with rejected posts, from troublemakers!
            factory(Signup::class, rand(10, 20))->create(['campaign_id' => $campaign->id])
                ->each(function (Signup $signup) use ($photoAction, $textAction) {
                    $signup->posts()->save(factory(Post::class, 'photo-rejected')->create([
                        'action_id' => $photoAction->id,
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
