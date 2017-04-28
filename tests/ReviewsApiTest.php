<?php

use Rogue\Models\Event;
use Rogue\Models\Photo;
use Rogue\Models\Post;
use Faker\Generator;

class ReviewsApiTest extends TestCase
{
    /*
     * Base URL for the Api.
     */
    protected $reviewsApiUrl = 'api/v2/reviews';

    /**
     * Test that a PUT request to /reviews updates the post's status and creates a new event and review.
     *
     * PUT /reviews
     * @return void
     */
    public function testUpdatingASingleReview()
    {

        // Create a post.
        $post = factory(Post::class)->create();

        $review = [
            'post_id' => $post->_id,
            'status' => $this->faker->word(),
            'admin_northstar_id' => str_random(24),
        ];

        $this->json('PUT', $this->reviewsApiUrl, $review);

        $this->assertResponseStatus(201);

        $response = $this->decodeResponseJson();

        // Make sure we created a event for the review.
        $this->seeInDatabase('events', [
            'created_at' => $response['data']['created_at'],
        ]);

        // Make sure a review is created.
        $this->seeInDatabase('reviews', [
            'post_id' => $response['data']['id'],
        ]);

        // Make sure the status is updated.
        $this->seeInDatabase('posts', [
            'status' => $response['data']['status'],
        ]);
    }
}
