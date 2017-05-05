<?php

use Rogue\Models\Event;
use Rogue\Models\Photo;
use Rogue\Models\Post;
use Faker\Generator;

class ReviewsTest extends TestCase
{
    /*
     * Base URL for the Api.
     */
    protected $reviewsUrl = 'api/v2/reviews';

    /**
     * Test that a PUT request to /reviews updates the post's status and creates a new event and review.
     *
     * PUT /reviews
     * @return void
     */
    public function testUpdatingASingleReview()
    {
        $user = factory(User::class, 'admin');

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
}
