<?php

use Rogue\Models\Post;
use Rogue\Models\Action;
use Rogue\Models\Signup;
use Rogue\Models\Campaign;
use Rogue\Models\ActionStat;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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

            $approvedQuantityBySchoolId = [];

            // Create 10-20 signups with one accepted photo post & some pending photo and text posts.
            factory(Signup::class, rand(10, 20))->create(['campaign_id' => $campaign->id])
                ->each(function (Signup $signup) use (&$approvedQuantityBySchoolId, $photoAction, $textAction) {
                    $acceptedPhotoPost = factory(Post::class)->states('photo', 'accepted')->create([
                        'action_id' => $photoAction->id,
                        'signup_id' => $signup->id,
                        'campaign_id' => $signup->campaign_id,
                        'northstar_id' => $signup->northstar_id,
                    ]);

                    $schoolId = $acceptedPhotoPost->school_id;

                    /**
                     * If this accepted photo post has a school, add its quantity to a running total
                     * of the school's approved quantity, that we'll use to create an ActionStat.
                     */
                    if (isset($schoolId) && ! isset($approvedQuantityBySchoolId[$schoolId])) {
                        $approvedQuantityBySchoolId[$schoolId] = $acceptedPhotoPost->quantity;
                    } elseif (isset($schoolId)) {
                        $approvedQuantityBySchoolId[$schoolId] += $acceptedPhotoPost->quantity;
                    }

                    $signup->posts()->save($acceptedPhotoPost);

                    $signup->posts()->saveMany(factory(Post::class, rand(2, 4))->states('photo', 'pending')->create([
                        'action_id' => $photoAction->id,
                        'signup_id' => $signup->id,
                        'campaign_id' => $signup->campaign_id,
                        'northstar_id' => $signup->northstar_id,
                    ]));

                    $signup->posts()->saveMany(factory(Post::class, rand(2, 4))->states('text', 'pending')->create([
                        'action_id' => $textAction->id,
                        'signup_id' => $signup->id,
                        'campaign_id' => $signup->campaign_id,
                        'northstar_id' => $signup->northstar_id,
                    ]));
                });

            // Create 5-10 signups with only accepted posts, from lil' angels!
            factory(Signup::class, rand(10, 20))->create(['campaign_id' => $campaign->id])
                ->each(function (Signup $signup) use (&$approvedQuantityBySchoolId, $photoAction, $textAction) {
                    $acceptedPhotoPost = factory(Post::class)->states('photo', 'accepted')->create([
                        'action_id' => $photoAction->id,
                        'signup_id' => $signup->id,
                        'campaign_id' => $signup->campaign_id,
                        'northstar_id' => $signup->northstar_id,
                    ]);

                    $schoolId = $acceptedPhotoPost->school_id;

                    if (isset($schoolId) && ! isset($approvedQuantityBySchoolId[$schoolId])) {
                        $approvedQuantityBySchoolId[$schoolId] = $acceptedPhotoPost->quantity;
                    } elseif (isset($schoolId)) {
                        $approvedQuantityBySchoolId[$schoolId] += $acceptedPhotoPost->quantity;
                    }

                    $signup->posts()->save($acceptedPhotoPost);

                    $signup->posts()->saveMany(factory(Post::class, rand(2, 4))->states('text', 'accepted')->create([
                        'action_id' => $textAction->id,
                        'signup_id' => $signup->id,
                        'campaign_id' => $signup->campaign_id,
                        'northstar_id' => $signup->northstar_id,
                    ]));
                });

            // Create 5-10 signups with rejected posts, from troublemakers!
            factory(Signup::class, rand(10, 20))->create(['campaign_id' => $campaign->id])
                ->each(function (Signup $signup) use ($photoAction, $textAction) {
                    $signup->posts()->save(factory(Post::class)->states('photo', 'rejected')->create([
                        'action_id' => $photoAction->id,
                        'signup_id' => $signup->id,
                        'campaign_id' => $signup->campaign_id,
                        'northstar_id' => $signup->northstar_id,
                    ]));
                });

            // Create 100 signups with no posts yet.
            factory(Signup::class, 100)->create(['campaign_id' => $campaign->id]);

            // Create action stats from the approved photo posts created for the photo action.
            foreach ($approvedQuantityBySchoolId as $schoolId => $total) {
                factory(ActionStat::class)->create([
                    'accepted_quantity' => $total,
                    'action_id' => $photoAction->id,
                    'school_id' => $schoolId,
                ]);
            }
        });

        // And two campaigns with no activity yet.
        factory(Campaign::class, 2)->create();
    }
}
