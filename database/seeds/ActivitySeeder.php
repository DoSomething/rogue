<?php

use Rogue\Models\Post;
use Rogue\Models\Photo;
use Rogue\Models\Signup;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 20 signups and activity under those signups.
        factory(Signup::class, 20)->create()->each(function($signup) {
            // For each signup, create 3 photos and a post for each photo.
            // @TODO - this will be temporary until everything lives in the posts table.
            $photos = factory(Photo::class, 3)->create();

            $photos->each(function($item, $key) use ($signup) {
                $post = factory(Post::class)->make([
                    'signup_id' => $signup->id,
                    'northstar_id' => $signup->northstar_id,
                ]);

                $item->post()->save($post);
            });
        });
    }
}
