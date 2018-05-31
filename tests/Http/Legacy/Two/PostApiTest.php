<?php

namespace Tests\Http\Legacy\Two;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\Signup;
use DoSomething\Gateway\Blink;
use Illuminate\Http\UploadedFile;

class PostApiTest extends TestCase
{
    /**
     * Test that a POST request to /posts creates a new
     * photo post and signup, if it doesn't already exist.
     *
     * @return void
     */
    public function testCreatingAPostAndSignup()
    {
        $northstar_id = $this->faker->uuid;
        $campaign_id = $this->faker->randomNumber(4);
        $campaign_run_id = $this->faker->randomNumber(4);
        $quantity = 10;
        $caption = $this->faker->sentence;

        // Mock the Blink API calls.
        $this->mock(Blink::class)
            ->shouldReceive('userSignup')
            ->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withRogueApiKey()->json('POST', 'api/v2/posts', [
            'northstar_id'     => $northstar_id,
            'campaign_id'      => $campaign_id,
            'campaign_run_id'  => $campaign_run_id,
            'quantity'         => $quantity,
            'type'             => 'photo',
            'action'           => 'default',
            'why_participated' => $this->faker->paragraph,
            'num_participants' => null,
            'caption'          => $caption,
            'source'           => 'phpunit',
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
            'crop_x'           => 0,
            'crop_y'           => 0,
            'crop_width'       => 100,
            'crop_height'      => 100,
            'crop_rotate'      => 90,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'northstar_id' => $northstar_id,
                'status' => 'pending',
                'media' => [
                    'caption' => $caption,
                ],
                'type' => 'photo',
                'action' => 'default',
            ],
        ]);

        // Make sure the signup & post are persisted to the database.
        $this->assertDatabaseHas('signups', [
            'campaign_id' => $campaign_id,
            'northstar_id' => $northstar_id,
        ]);

        $this->assertDatabaseHas('posts', [
            'northstar_id' => $northstar_id,
            'campaign_id' => $campaign_id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test that a POST request to /posts creates a new photo post.
     *
     * @return void
     */
    public function testCreatingAPost()
    {
        $signup = factory(Signup::class)->create();
        $quantity = 10;
        $caption = $this->faker->sentence;

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withRogueApiKey()->json('POST', 'api/v2/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'quantity'         => $quantity,
            'why_participated' => $this->faker->paragraph,
            'num_participants' => null,
            'caption'          => $caption,
            'source'           => 'phpunit',
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
            'crop_x'           => 0,
            'crop_y'           => 0,
            'crop_width'       => 100,
            'crop_height'      => 100,
            'crop_rotate'      => 90,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'northstar_id' => $signup->northstar_id,
                'status' => 'pending',
                // If we are supporting quantity on the post, this value will be the submitted quantity,
                // otherwise, we don't put anything on the post and it will be null.
                'quantity' => config('features.v3QuantitySupport') ? $quantity : null,
                'media' => [
                    'caption' => $caption,
                ],
            ],
        ]);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test that a POST request to /posts with type and action creates a new photo post.
     *
     * @return void
     */
    public function testCreatingAPostWithTypeAndAction()
    {
        $signup = factory(Signup::class)->create();
        $quantity = 10;
        $caption = $this->faker->sentence;

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withRogueApiKey()->json('POST', 'api/v2/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'quantity'         => $quantity,
            'type'             => 'photo',
            'action'           => 'default',
            'why_participated' => $this->faker->paragraph,
            'num_participants' => null,
            'caption'          => $caption,
            'source'           => 'phpunit',
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
            'crop_x'           => 0,
            'crop_y'           => 0,
            'crop_width'       => 100,
            'crop_height'      => 100,
            'crop_rotate'      => 90,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'northstar_id' => $signup->northstar_id,
                'status' => 'pending',
                // If we are supporting quantity on the post, this value will be the submitted quantity,
                // otherwise, we don't put anything on the post and it will be null.
                'quantity' => config('features.v3QuantitySupport') ? $quantity : null,
                'media' => [
                    'caption' => $caption,
                ],
                'type' => 'photo',
                'action' => 'default',
            ],
        ]);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'status' => 'pending',
            'type' => 'photo',
            'action' => 'default',
        ]);
    }

    /**
     * Test that a POST request to /posts with an existing post creates an additional new photo post.
     *
     * @return void
     */
    public function testCreatingMultiplePosts()
    {
        $signup = factory(Signup::class)->create();
        $quantity = 30;
        $caption = $this->faker->sentence;

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withRogueApiKey()->json('POST', 'api/v2/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'quantity'         => $quantity,
            'why_participated' => $this->faker->paragraph,
            'num_participants' => null,
            'caption'          => $caption,
            'source'           => 'phpunit',
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
            'crop_x'           => 0,
            'crop_y'           => 0,
            'crop_width'       => 100,
            'crop_height'      => 100,
            'crop_rotate'      => 90,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'northstar_id' => $signup->northstar_id,
                'status' => 'pending',
                // If we are supporting quantity on the post, this value will be the submitted quantity,
                // otherwise, we don't put anything on the post and it will be null.
                'quantity' => config('features.v3QuantitySupport') ? $quantity : null,
                'media' => [
                    'caption' => $caption,
                ],
            ],
        ]);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'status' => 'pending',
        ]);

        // Create a second post without why_participated.
        $secondQuantity = 40;
        $secondCaption = $this->faker->sentence;

        $secondResponse = $this->withRogueApiKey()->json('POST', 'api/v2/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'quantity'         => $secondQuantity,
            'num_participants' => null,
            'caption'          => $secondCaption,
            'source'           => 'phpunit',
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
            'crop_x'           => 0,
            'crop_y'           => 0,
            'crop_width'       => 100,
            'crop_height'      => 100,
            'crop_rotate'      => 90,
        ]);

        $secondResponse->assertStatus(200);

        $secondResponse->assertJson([
            'data' => [
                'northstar_id' => $signup->northstar_id,
                'status' => 'pending',
                // If we are supporting quantity on the post,
                // this value should be the the new, posted quantity minus
                // the previous total, otherwise it is null.
                'quantity' => config('features.v3QuantitySupport') ? 10 : null,
                'media' => [
                    'caption' => $secondCaption,
                ],
            ],
        ]);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test that we can make POST requess to /posts creates a new photo post
     * when the campaign id and there is no campaign run id.
     * (Mimics requests from phoenix-next)
     *
     * @return void
     */
    public function testCreatingAPostFromContentful()
    {
        $signup = factory(Signup::class)->states('contentful')->create();
        $quantity = 10;
        $caption = $this->faker->sentence;

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withRogueApiKey()->json('POST', 'api/v2/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'quantity'         => $quantity,
            'why_participated' => $this->faker->paragraph,
            'num_participants' => null,
            'caption'          => $caption,
            'source'           => 'phpunit',
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
            'crop_x'           => 0,
            'crop_y'           => 0,
            'crop_width'       => 100,
            'crop_height'      => 100,
            'crop_rotate'      => 90,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'northstar_id' => $signup->northstar_id,
                'status' => 'pending',
                // If we are supporting quantity on the post, this value will be the submitted quantity,
                // otherwise, we don't put anything on the post and it will be null.
                'quantity' => config('features.v3QuantitySupport') ? $quantity : null,
                'media' => [
                    'caption' => $caption,
                ],
            ],
        ]);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'status' => 'pending',
        ]);
    }

    /**
     * Turn on quantity splitting and make sure we are handling quantity correctly.
     *
     * GET /signups
     * @return void
     */
    public function testSplittingQuantityOnPosts()
    {
        // Turn on feature flag that supports quantity splitting.
        config(['features.v3QuantitySupport' => true]);

        // Create a signup with no quantity.
        $signup = factory(Signup::class)->create();

        // Make sure the signup is persisted with no quantity.
        $this->assertDatabaseHas('signups', [
            'id' => $signup->id,
            'quantity' => null,
        ]);

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the first post via API with quantity 10.
        $firstPost = $this->withRogueApiKey()->json('POST', 'api/v2/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'quantity'         => 10,
            'caption'          => 'Fake caption',
            'source'           => 'testing',
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
        ])->decodeResponseJson();

        // Confirm quantity is persisted between signups and posts correctly.
        $this->assertDatabaseHas('signups', [
            'id' => $signup->id,
            'quantity' => 10,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $firstPost['data']['id'],
            'quantity' => 10,
        ]);

        // Create another post with a new quantity.
        $secondPost = $this->withRogueApiKey()->json('POST', 'api/v2/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'quantity'         => 20,
            'caption'          => 'Fake caption',
            'source'           => 'testing',
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
        ])->decodeResponseJson();

        // Confirm quantity is persisted between signups and posts correctly.
        $this->assertDatabaseHas('signups', [
            'id' => $signup->id,
            'quantity' => 20,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $firstPost['data']['id'],
            'quantity' => 10,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $secondPost['data']['id'],
            'quantity' => 10,
        ]);

        // Create another post with a less quantity.
        $thirdPost = $this->withRogueApiKey()->json('POST', 'api/v2/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'quantity'         => 5,
            'caption'          => 'Fake caption',
            'source'           => 'testing',
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
        ])->decodeResponseJson();

        // Confirm quantity is persisted between signups and posts correctly.
        $this->assertDatabaseHas('signups', [
            'id' => $signup->id,
            'quantity' => 25,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $firstPost['data']['id'],
            'quantity' => 10,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $secondPost['data']['id'],
            'quantity' => 10,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $thirdPost['data']['id'],
            'quantity' => 5,
        ]);

        // Create another post with the same quantity as the total.
        $fourthPost = $this->withRogueApiKey()->json('POST', 'api/v2/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'quantity'         => 25,
            'caption'          => 'Fake caption',
            'source'           => 'testing',
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
        ])->decodeResponseJson();

        // Confirm quantity is persisted between signups and posts correctly.
        $this->assertDatabaseHas('signups', [
            'id' => $signup->id,
            'quantity' => 25,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $firstPost['data']['id'],
            'quantity' => 10,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $secondPost['data']['id'],
            'quantity' => 10,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $thirdPost['data']['id'],
            'quantity' => 5,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $fourthPost['data']['id'],
            'quantity' => 0,
        ]);
    }

    /**
     * Turn off quantity splitting and make sure we are handling quantity correctly.
     *
     * GET /signups
     * @return void
     */
    public function testNotSplittingQuantityOnPosts()
    {
        // Turn off feature flag that supports quantity splitting.
        config(['features.v3QuantitySupport' => false]);

        // Create a signup with no quantity.
        $signup = factory(Signup::class)->create();

        // Make sure the signup is persisted with no quantity.
        $this->assertDatabaseHas('signups', [
            'id' => $signup->id,
            'quantity' => null,
        ]);

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the first post via API with quantity 10.
        $firstPost = $this->withRogueApiKey()->json('POST', 'api/v2/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'quantity'         => 10,
            'caption'          => 'Fake caption',
            'source'           => 'testing',
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
        ])->decodeResponseJson();

        // Confirm quantity is persisted between signups and posts correctly.
        $this->assertDatabaseHas('signups', [
            'id' => $signup->id,
            'quantity' => 10,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $firstPost['data']['id'],
            'quantity' => null,
        ]);

        // Create another post with a new quantity.
        $secondPost = $this->withRogueApiKey()->json('POST', 'api/v2/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'quantity'         => 20,
            'caption'          => 'Fake caption',
            'source'           => 'testing',
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
        ])->decodeResponseJson();

        // Confirm quantity is persisted between signups and posts correctly.
        $this->assertDatabaseHas('signups', [
            'id' => $signup->id,
            'quantity' => 20,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $firstPost['data']['id'],
            'quantity' => null,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $secondPost['data']['id'],
            'quantity' => null,
        ]);
    }
}
