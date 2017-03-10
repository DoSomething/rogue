<?php

use Rogue\Models\Post;
use Rogue\Models\Photo;
use Rogue\Models\Event;
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
        $photo = factory(Photo::class)->create();

        // Create the post and associate it with the photo.
        $post = factory(Post::class)->create([
            'postable_id' => $photo->id,
            'postable_type' => 'Rogue\Models\Photo',
        ]);
        $post->content()->associate($photo);
        $post->save();

        // Associate the post event with the signup.
        $postEvent = $post->event;
        $postEvent->signup_id = $post->signup->id;
        $postEvent->save();

        // Associate the signup event with the signup.
        $signupEventId = $post->signup->event_id;
        $signupEvent = Event::where('id', $signupEventId)->first();
        $signupEvent->signup_id = $post->signup->id;
        $signupEvent->save();

    }
}
