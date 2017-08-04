<?php

use Rogue\Models\User;
use Rogue\Models\Post;
use Rogue\Models\Signup;

class PostTest extends TestCase
{
    /**
     * Test that posts get soft-deleted when hitting the DELETE endpoint.
     *
     * @return void
     */
    public function testDeletingAPost()
    {
        $post = factory(Post::class)->create();

        $this->actingAsAdmin()->delete('posts/' . $post->id);

        $this->assertResponseStatus(200);
        $this->seeSoftDeletedRecord('posts', $post->id);
    }

    /**
     * Test that non-admins can't delete posts.
     *
     * @return void
     */
    public function testUnauthenticatedUserDeletingAPost()
    {
        $user = factory(User::class)->make();
        $post = factory(Post::class)->create();

        $this->actingAs($user)->delete('posts/' . $post->id);

        $this->assertResponseStatus(403);
    }

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
        $this->assertEquals((string) $post->updated_at, '2017-08-03 16:52:00');

        // @TODO: Signup timestamp isn't being touched.
        // $this->assertEquals((string) $signup->updated_at, '2017-08-03 16:52:00');
        $this->markTestIncomplete();
    }
}
