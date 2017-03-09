<?php

use Rogue\Models\Post;
use Rogue\Models\Photo;
use Illuminate\Database\Seeder;

class PhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $photo = factory(Photo::class)->create();

        $post = factory(Post::class)->create([
            'postable_id' => $photo->id,
            'postable_type' => 'Rogue\Models\Photo',
        ]);
        $post->content()->associate($photo);

        $postEvent = $post->event;
        $postEvent->signup_id = $post->signup->id;
        $postEvent->save();

        $post->save();
    }
}
