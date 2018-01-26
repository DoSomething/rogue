<?php

namespace Tests\Http\Three;

use Tests\TestCase;
use Rogue\Models\Post;

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
        $this->mockTime('8/3/2017 17:02:00');

        // Create a post.
        $northstarId = $this->faker->northstar_id;
        $post = factory(Post::class)->create();

        $response = $this->withAccessToken($northstarId, 'admin')->postJson('api/v3/reviews', [
            'post_id' => $post->id,
            'status' => 'accepted',
            'comment' => 'testing',
        ]);

        $response->assertStatus(201);

        // Make sure the post status is updated & a review is created.
        $this->assertEquals('accepted', $post->fresh()->status);
        $this->assertDatabaseHas('reviews', [
            'admin_northstar_id' => $northstarId,
            'post_id' => $post->id,
            'comment' => 'testing',
        ]);
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

        $response = $this->postJson('api/v3/reviews', [
            'post_id' => $post->id,
            'status' => 'accepted',
        ]);

        $response->assertStatus(401);
    }
}
