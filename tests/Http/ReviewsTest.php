<?php

namespace Tests\Http;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\User;
use Illuminate\Support\Facades\Bus;
use Rogue\Jobs\SendReviewedPostToCIO;

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
        Bus::fake();

        $this->mockTime('8/3/2017 17:02:00');

        // Create a post.
        $user = factory(User::class, 'admin')->create();
        $post = factory(Post::class)->create();

        $response = $this->actingAs($user)->putJson('reviews', [
            'post_id' => $post->id,
            'status' => 'accepted',
        ]);

        $response->assertStatus(201);
        Bus::assertDispatched(SendReviewedPostToCIO::class);

        // Make sure the post status is updated & a review is created.
        $this->assertEquals('accepted', $post->fresh()->status);
        $this->assertDatabaseHas('reviews', [
            'admin_northstar_id' => $user->northstar_id,
            'post_id' => $post->id,
        ]);

        // Make sure we created an event for the review.
        $this->assertDatabaseHas('events', [
            'eventable_type' => 'Rogue\Models\Review',
            'created_at' => '2017-08-03 17:02:00',
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

        $response = $this->actingAs($user)->putJson('reviews', [
            'post_id' => $post->id,
            'status' => 'accepted',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test that a post and signup's updated_at updates when a review is made.
     *
     * @return void
     */
    public function testUpdatedPostAndSignupWithReview()
    {
        // Create a signup and a post, and associate them to each other.
        $post = factory(Post::class)->create();

        // And then later on, we'll make a review...
        $this->mockTime('8/3/2017 16:55:00');

        // Review the post.
        $this->actingAsAdmin()->putJson('reviews', [
            'post_id' => $post->id,
            'status' => 'accepted',
        ]);

        // Make sure the signup and post's updated_at are both updated.
        $this->assertEquals('2017-08-03 16:55:00', (string) $post->fresh()->updated_at);

        // @TODO: Laravel doesn't touch timestamps recursively - only direct relationships.
        // $this->assertEquals('2017-08-03 16:55:00', (string) $signup->fresh()->updated_at);
        $this->markTestIncomplete();
    }
}
