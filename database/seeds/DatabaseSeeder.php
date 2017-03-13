<?php

use Rogue\Models\Post;
use Rogue\Models\Photo;
use Rogue\Models\Event;
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
        $photo = factory(Photo::class)->create();

        // Create the post and associate it with the photo.
        $post = factory(Post::class)->create([
            'postable_id' => $photo->id,
            'postable_type' => 'Rogue\Models\Photo',
        ]);
        $post->content()->associate($photo);
        $post->save();

        // Associate the post event with the signup and fill in event columns.
        $postEvent = $post->event;
        $postEvent->signup_id = $post->signup->id;
        $postEvent->event_type = 'post_photo';
        $postEvent->quantity_pending = $post->signup->quantity_pending;
        $postEvent->why_participated = $post->signup->why_participated;
        $postEvent->caption = $photo->caption;
        $postEvent->status = 'pending';
        $postEvent->source = $post->signup->source;
        // Fill the post event northstar_id with the post northstar_id so all is associated with the same user.
        $postEvent->northstar_id = $post->northstar_id;
        $postEvent->save();

        // Fill the signup northstar_id with the post northstar_id.
        $post->signup->northstar_id = $post->northstar_id;
        $post->signup->save();

        // Associate the signup event with the signup.
        $signupEventId = $post->signup->event_id;
        $signupEvent = Event::where('id', $signupEventId)->first();
        // We need to figure out how to associate the signup event to the signup for it to work in the SignupTransformer.
        // $signupEvent->signup() = $post->signup;
        // $signup->events()->associate($signupEvent);
        $post->signup->events()->save($signupEvent);
        dd($post->signup->events()->first());

        // $signup->save();
        // dd($signup->events->first());
        $signupEvent->signup_id = $post->signup->id;
        $signupEvent->event_type = 'signup';

        // Fill the signup event northstar_id with the post_northstar_id so all is associated with the same user.
        $signupEvent->northstar_id = $post->northstar_id;
        $signupEvent->save();
    }
}
