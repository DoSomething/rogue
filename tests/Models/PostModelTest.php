<?php

use Rogue\Models\Post;
use Rogue\Models\Signup;

class PostModelTest extends TestCase
{
    /**
     * Test that a signup's updated_at updates when a post is updated.
     *
     * @return void
     */
    public function testUpdatingSignupTimestampWhenPostIsUpdated()
    {
        // Freeze time since we're making assertions on timestamps.
        $this->mockTime('8/3/2017 14:00:00');

        // Create a signup and a post, and associate them to each other.
        $signup = factory(Signup::class)->create();
        $post = factory(Post::class)->create();
        $post->signup()->associate($signup);

        // And then later on, we'll update the post...
        $this->mockTime('8/3/2017 16:52:00');
        $post->update(['caption' => 'new caption']);

        // Make sure the signup and post's updated_at are both updated.
        $this->assertEquals('2017-08-03 16:52:00', (string) $post->fresh()->updated_at);
        $this->assertEquals('2017-08-03 16:52:00', (string) $signup->fresh()->updated_at);
    }

    /**
     * Test that the siblings relationship returns other posts.
     *
     * @return void
     */
    public function testSiblingRelationship()
    {
        factory(Signup::class, 5)->create()
            ->each(function ($signup) {
                $signup->posts()->saveMany(factory(Post::class, 'accepted', 3)->create());
            });

        // Grab any old post.
        $post = Signup::all()->first()->posts->first();

        // Asking for the siblings of the post, should only give other
        // posts with the same `signup_id` (including itself).
        $this->assertCount(3, $post->siblings);
        $this->assertEquals($post->signup_id, $post->siblings[0]->signup_id);
    }
}
