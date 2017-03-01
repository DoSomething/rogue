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
     * Test that a PUT request to /reviews updates the status and creates a new event and review.
     *
     * PUT /reviews
     * @return void
     */
    public function testUpdatingASingleReview()
    {
        // Create a post.
        $post = factory(Post::class)->create();
        // dd($post->event_id);
        // Wrapped in an array because reviews are pushed into an $updates_to_send_to_rogue array in Phoenix.
        $review = [
            [
                'rogue_event_id' => $post->event_id,
                'status' => 'pending',
                'event_type' => 'review_photo',
                'reviewer' => str_random(24),
            ]
        ];

        $response = $this->json('PUT', $this->reviewsApiUrl, $review);
        dd($response);
        // $this->assertResponseStatus(200);

        // Make sure we created a event for the review.
        // $this->seeInDatabase('events', [
        //     'caption' => $response['data']['content']['caption'],
        // ]);

        // Make sure a review is created.

        // Make sure the status is updated.
    }

    // Test multiple rbs

}
