<?php

namespace Tests\Http;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\Signup;

class EventTest extends TestCase
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
        $this->assertDatabaseHas('events', [
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
        $this->assertDatabaseHas('events', [
            'eventable_type' => 'Rogue\Models\Signup',
            'created_at' => '2017-08-03 17:02:00',
        ]);

        // Make sure we also created an event for the signup update.
        $this->assertDatabaseHas('events', [
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
        $this->assertDatabaseHas('events', [
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
        $this->assertDatabaseHas('events', [
            'eventable_type' => 'Rogue\Models\Post',
            'created_at' => '2017-08-03 17:02:00',
        ]);

        // Make sure we also created an event for the post update.
        $this->assertDatabaseHas('events', [
            'eventable_type' => 'Rogue\Models\Post',
            'created_at' => '2017-08-04 18:02:00',
        ]);

        // Make sure a signup event wasn't created when the post was updated.
        $this->assertDatabaseMissing('events', [
            'eventable_type' => 'Rogue\Models\Signup',
            'created_at' => '2017-08-04 18:02:00',
        ]);
    }

    /**
     * Test deleting a post via the API (DELETE 'posts/{id}') creates an event.
     *
     * @return void
     */
    public function testDeletingPostViaApiEvent()
    {
        // Create a post.
        $post = factory(Post::class)->create();

        // Delete the post via the API.
        $this->mockTime('8/4/2017 18:02:00');
        $this->actingAsAdmin()->deleteJson('posts/' . $post->id);

        // Make sure an event is created when the post is deleted.
        $this->assertDatabaseHas('events', [
            'eventable_type' => 'Rogue\Models\Post',
            'created_at' => '2017-08-04 18:02:00',
        ]);
    }
}
