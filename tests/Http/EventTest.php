<?php

namespace Tests\Http;

use Rogue\Models\Post;
use Rogue\Models\Signup;
use Tests\BrowserKitTestCase;

class EventTest extends BrowserKitTestCase
{
    /**
     * Test that an event gets created when a signup is created.
     *
     * @return void
     */
    public function testCreatingSignupEvent()
    {
        // Create a signup
        $this->mockTime('8/3/2017 17:02:00');
        factory(Signup::class)->create();

        // Make sure we created an event for the signup creation.
        $this->seeInDatabase('events', [
            'eventable_type' => 'Rogue\Models\Signup',
            'created_at' => '2017-08-03 17:02:00',
        ]);
    }

    /**
     * Test that an event gets created when a signup is updated.
     *
     * @return void
     */
    public function testUpdatingSignupEvent()
    {
        // Create a signup
        $this->mockTime('8/3/2017 17:02:00');
        $signup = factory(Signup::class)->create();

        // And then later on, we'll update this signup...
        $this->mockTime('8/4/2017 18:02:00');
        $signup->quantity = $signup->quantity + 1;
        $signup->save();

        // Make sure we created an event for the signup creation.
        $this->seeInDatabase('events', [
            'eventable_type' => 'Rogue\Models\Signup',
            'created_at' => '2017-08-03 17:02:00',
        ]);

        // Make sure we also created an event for the signup update.
        $this->seeInDatabase('events', [
            'eventable_type' => 'Rogue\Models\Signup',
            'created_at' => '2017-08-04 18:02:00',
        ]);
    }

    /**
     * Test that an event get created when a post is created.
     *
     * @return void
     */
    public function testCreatingPostEvent()
    {
        // Create a post
        $this->mockTime('8/3/2017 17:02:00');
        factory(Post::class)->create();

        // Make sure we created an event for the post creation.
        $this->seeInDatabase('events', [
            'eventable_type' => 'Rogue\Models\Post',
            'created_at' => '2017-08-03 17:02:00',
        ]);
    }

    /**
     * Test that an event gets created when a post is updated (and that a signup event doesn't get updated).
     *
     * @return void
     */
    public function testUpdatingPostEvent()
    {
        // Create a signup
        $signup = factory(Signup::class)->create();

        // Create a post and associate it to the signup.
        $this->mockTime('8/3/2017 17:02:00');
        $post = factory(Post::class)->create();
        $post->signup()->associate($signup);

        // And then later on, we'll update this post...
        $this->mockTime('8/4/2017 18:02:00');
        $post->status = 'rejected';
        $post->save();

        // Make sure we created an event for the post creation.
        $this->seeInDatabase('events', [
            'eventable_type' => 'Rogue\Models\Post',
            'created_at' => '2017-08-03 17:02:00',
        ]);

        // Make sure we also created an event for the post update.
        $this->seeInDatabase('events', [
            'eventable_type' => 'Rogue\Models\Post',
            'created_at' => '2017-08-04 18:02:00',
        ]);

        // Make sure a signup event wasn't created when the post was updated.
        $this->notSeeInDatabase('events', [
            'eventable_type' => 'Rogue\Models\Signup',
            'created_at' => '2017-08-04 18:02:00',
        ]);
    }
}
