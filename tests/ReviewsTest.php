<?php

use Rogue\Models\Event;
use Rogue\Models\Post;
use Rogue\Models\User;
use Rogue\Models\Signup;
use Faker\Generator;

class ReviewsTest extends TestCase
{
    /*
     * Base URL for the Api.
     */
    protected $reviewsUrl = 'reviews';

    /**
     * Test that a PUT request to /reviews updates the post's status and creates a new event and review.
     *
     * PUT /reviews
     * @return void
     */
    public function testUpdatingASingleReview()
    {
        $user = factory(User::class)->make([
            'role' => 'admin',
        ]);

        $this->be($user);

        // Create a post.
        $post = factory(Post::class)->create();
        $review = [
            'post_id' => $post->id,
            'status' => 'accepted',
        ];

        $this->json('PUT', $this->reviewsUrl, $review);
        $this->assertResponseStatus(201);

        $response = $this->decodeResponseJson();

        // Make sure we created a event for the review.
        $this->seeInDatabase('events', [
            'created_at' => $response['data']['created_at'],
        ]);

        // Make sure a review is created.
        $this->seeInDatabase('reviews', [
            'admin_northstar_id' => $user->northstar_id,
            'post_id' => $response['data']['id'],
        ]);

        // Make sure the status is updated.
        $this->seeInDatabase('posts', [
            'status' => $response['data']['status'],
        ]);
    }

    /**
     * Test that non-admin cannot review posts.
     *
     * @return void
     */
    public function testUnauthenticatedUserCantReviewPosts()
    {
        $user = factory(User::class)->make();

        $this->be($user);

        // Create a post.
        $post = factory(Post::class)->create();
        $review = [
            'post_id' => $post->id,
            'status' => 'accepted',
        ];

        $this->json('PUT', $this->reviewsUrl, $review);
        $this->assertResponseStatus(403);
    }

    /**
     * Test that a post and signup's updated_at updates when a review is made.
     *
     * @return void
     */
    public function testUpdatedPostAndSignupWithReview()
    {
        $user = factory(User::class)->make([
            'role' => 'admin',
        ]);

        $this->be($user);

        // Create a signup and a post, and associate them to each other.
        $signup = factory(Signup::class)->create();
        $post = factory(Post::class)->create();
        $post->signup()->associate($signup);

        // Wait 1 second before making a review to make sure the created_at and updated_at times are different.
        sleep(1);

        // Review the post.
        $review = [
            'post_id' => $post->id,
            'status' => 'accepted',
        ];

        $this->json('PUT', $this->reviewsUrl, $review);

        // Re-grab the updated signup and post from the database.
        $updatedSignup = Signup::where('id', $signup->id)->first();
        $updatedPost = Post::where('id', $post->id)->first();
        dd($updatedPost);

        // Make sure the signup and post's updated_at matches the reaction created_at time.
        $this->assertEquals($post->review->created_at, $updatedSignup->updated_at);
        $this->assertEquals($post->review->created_at, $updatedPost->updated_at);
    }
}
