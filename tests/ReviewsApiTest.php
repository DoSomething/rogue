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
        // Create a photo.
        $photo = factory(Photo::class)->create();

        // Create a post and associate the photo with it.
        $post = factory(Post::class)->create([
            'postable_id' => $photo->id,
            'postable_type' => 'Rogue\Models\Photo',
        ]);
        $post->content()->associate($photo);
        $post->save();

        // Wrapped in an array because reviews are pushed into an $updates_to_send_to_rogue array in Phoenix.
        $review = [
            [
                'rogue_event_id' => $post->event_id,
                'status' => 'accepted',
                'event_type' => 'review_photo',
                'reviewer' => str_random(24),
            ]
        ];

        $this->json('PUT', $this->reviewsApiUrl, $review);

        $this->assertResponseStatus(201);

        $response = $this->decodeResponseJson();

        // Make sure we created a event for the review.
        $this->seeInDatabase('events', [
            'status' => $response['data'][0]['post']['status'],
        ]);

        // Make sure a review is created.
        $this->seeInDatabase('reviews', [
            'northstar_id' => $response['data'][0]['northstar_id'],
        ]);

        // Make sure the status is updated.
        $this->seeInDatabase('photos', [
            'status' => $response['data'][0]['post']['status'],
        ]);
    }

    // Test multiple rbs

}
