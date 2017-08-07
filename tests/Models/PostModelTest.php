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
        $this->assertEquals((string)$post->updated_at, '2017-08-03 16:52:00');

        // @TODO: Signup timestamp isn't being touched.
        // $this->assertEquals((string) $signup->updated_at, '2017-08-03 16:52:00');
        $this->markTestIncomplete();
    }
}
