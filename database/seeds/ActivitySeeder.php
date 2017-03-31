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
        factory(Signup::class, 20)->create()->each(function ($signup) {
            // Create a signup for each post.
            $post = factory(Post::class)->create([
                'signup_id' => $signup->id,
                'northstar_id' => $signup->northstar_id,
            ]);
        });
    }
}
