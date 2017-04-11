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
     * @todo - The reviews endpoint needs to be updated to work
     * with the new schema and this test will have to be rewritten
     * when that happens.
     *
     * Test that a PUT request to /reviews updates the status and creates a new event and review.
     *
     * PUT /reviews
     * @return void
     */
    // public function testUpdatingASingleReview()
    // {
    //     // Create a photo.
    //     $photo = factory(Photo::class)->create();

    //     // Create a post and associate the photo with it.
    //     $post = factory(Post::class)->create([
    //         'postable_id' => $photo->id,
    //         'postable_type' => 'photo',
    //     ]);
    //     $post->content()->associate($photo);
    //     $post->save();

    //     $review = [
    //         'rogue_event_id' => $post->event_id,
    //         'status' => $this->faker->word(),
    //         'event_type' => 'review_photo',
    //         'reviewer' => str_random(24),
    //     ];

    //     $this->json('PUT', $this->reviewsApiUrl, $review);

    //     $this->assertResponseStatus(201);

    //     $response = $this->decodeResponseJson();

    //     // Make sure we created a event for the review.
    //     $this->seeInDatabase('events', [
    //         'status' => $response['data']['content']['status'],
    //     ]);

    //     // Make sure a review is created.
    //     $this->seeInDatabase('reviews', [
    //         'postable_id' => $response['data']['postable_id'],
    //     ]);

    //     // Make sure the status is updated.
    //     $this->seeInDatabase('photos', [
    //         'status' => $response['data']['content']['status'],
    //     ]);
    // }
}
