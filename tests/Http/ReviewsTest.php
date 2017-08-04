<?php

use Rogue\Models\Post;
use Rogue\Models\User;
use Rogue\Models\Signup;

class ReviewsTest extends TestCase
{
    /**
     * Test that a PUT request to /reviews updates the post's status and creates a new event and review.
     *
     * PUT /reviews
     * @return void
     */
    public function testUpdatingASingleReview()
    {
        $this->mockTime('8/3/2017 17:02:00');

        // Create a post.
        $user = factory(User::class, 'admin')->create();
        $post = factory(Post::class)->create();

        $this->actingAs($user)->put('reviews', [
            'post_id' => $post->id,
            'status' => 'accepted',
        ]);

        $this->assertResponseStatus(201);

        // Make sure the post status is updated & a review is created.
        $this->assertEquals('accepted', $post->fresh()->status);
        $this->seeInDatabase('reviews', [
            'admin_northstar_id' => $user->northstar_id,
            'post_id' => $post->id,
        ]);

        // Make sure we created an event for the review.
        $this->seeInDatabase('events', [
            'eventable_type' => 'Rogue\Models\Review',
            'created_at' => '2017-08-03 17:02:00'
        ]);
    }

    /**
     * Test that non-admin cannot review posts.
     *
     * @return void
     */
    public function testUnauthenticatedUserCantReviewPosts()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $this->actingAs($user)->put('reviews', [
            'post_id' => $post->id,
            'status' => 'accepted',
        ]);

        $this->assertResponseStatus(403);
    }

    /**
     * Test that a post and signup's updated_at updates when a review is made.
     *
     * @return void
     */
    public function testUpdatedPostAndSignupWithReview()
    {
        // @TODO: This isn't currently working.
        $this->markTestSkipped();

        // Create a signup and a post, and associate them to each other.
        $signup = factory(Signup::class)->create();
        $post = factory(Post::class)->create();
        $post->signup()->associate($signup);

        // And then later on, we'll make a review...
        $this->mockTime('8/3/2017 16:55:00');

        // Review the post.
        $this->actingAsAdmin()->put('reviews', [
            'post_id' => $post->id,
            'status' => 'accepted',
        ]);

        // Make sure the signup and post's updated_at are both updated.
        $this->assertEquals('2017-08-03 16:55:00', (string) $signup->fresh()->updated_at);
        $this->assertEquals('2017-08-03 16:55:00', (string) $post->fresh()->updated_at);
    }
}
