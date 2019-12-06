<?php

namespace Tests\Http;

use Tests\TestCase;
use Rogue\Models\Post;
use Illuminate\Support\Facades\Bus;
use Rogue\Jobs\SendReviewedPostToCustomerIo;

class ReviewsTest extends TestCase
{
    /**
     * Test that a POST request to /reviews updates the post's status.
     *
     * POST /reviews
     * @return void
     */
    public function testPostingASingleReview()
    {
        Bus::fake();

        // Create a post.
        $northstarId = $this->faker->northstar_id;

        $post = factory(Post::class)->create();

        $response = $this->withAccessToken($northstarId, 'admin')->postJson('api/v3/posts/' . $post->id . '/reviews', [
            'status' => 'accepted',
            'comment' => 'testing',
        ]);

        $response->assertStatus(201);
        Bus::assertDispatched(SendReviewedPostToCustomerIo::class);


        $this->assertEquals('accepted', $post->fresh()->status);
        $this->assertDatabaseHas('reviews', [
            'admin_northstar_id' => $northstarId,
            'post_id' => $post->id,
            'comment' => 'testing',
        ]);
        $this->assertDatabaseHas('campaigns', [
            'id' => $post->campaign->id,
            'accepted_count' => 1,
        ]);
        $this->assertDatabaseHas('action_stats', [
            'action_id' => $post->action_id,
            'accepted_quantity' => $post->quantity,
            'school_id' => $post->school_id,
        ]);
    }

    /**
     * Test that a POST request to /reviews without activity scope.
     *
     * POST /reviews
     * @return void
     */
    public function testPostingASingleReviewWithoutActivityScope()
    {
        Bus::fake();

        // Create a post.
        $northstarId = $this->faker->northstar_id;
        $post = factory(Post::class)->create();

        $response = $this->postJson('api/v3/posts/' . $post->id . '/reviews', [
            'status' => 'accepted',
            'comment' => 'testing',
        ]);

        $response->assertStatus(401);
        $this->assertEquals('Unauthenticated.', $response->decodeResponseJson()['message']);
    }

    /**
     * Test that non-admin cannot review posts.
     *
     * @return void
     */
    public function testUnauthenticatedUserCantReviewPosts()
    {
        // $northstarId = $this->faker->northstar_id;
        $post = factory(Post::class)->create();

        $response = $this->postJson('api/v3/posts/' . $post->id . '/reviews', [
            'status' => 'accepted',
        ]);

        $response->assertStatus(401);
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
        $northstarId = $this->faker->northstar_id;
        $this->withAccessToken($northstarId, 'admin')->postJson('api/v3/posts/' . $post->id . '/reviews', [
            'status' => 'accepted',
        ]);

        // Make sure the post's updated_at matches the review time.
        $this->assertEquals('2017-08-03 16:55:00', (string) $post->fresh()->updated_at);
    }

    /**
     * Test that you get a 404 if the post doesn't exist.
     *
     * @return void
     */
    public function test404IfPostDoesntExist()
    {
        // Review a post that doesn't exist.
        $northstarId = $this->faker->northstar_id;
        $response = $this->withAccessToken($northstarId, 'admin')->postJson('api/v3/reviews/posts/z/reviews', [
            'post_id' => 88,
            'status' => 'accepted',
        ]);

        $response->assertStatus(404);
    }
}
