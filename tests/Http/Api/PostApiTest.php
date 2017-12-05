<?php

namespace Tests\Http\Api;

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
        $quantity = $this->faker->numberBetween(10, 1000);
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
            'why_participated' => $this->faker->paragraph,
            'num_participants' => null,
            'caption'          => $caption,
            'source'           => 'phpunit',
            'remote_addr'      => $this->faker->ipv4,
            'file'             => UploadedFile::fake()->image('photo.jpg'),
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
        $quantity = $this->faker->numberBetween(10, 1000);
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
            'remote_addr'      => $this->faker->ipv4,
            'file'             => UploadedFile::fake()->image('photo.jpg'),
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
                'quantity' => $signup->getQuantity(),
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
     * Test that we can make POST requess to /posts creates a new photo post
     * when the campaign id and there is no campaign run id.
     * (Mimics requests from phoenix-next)
     *
     * @return void
     */
    public function testCreatingAPostFromContentful()
    {
        $signup = factory(Signup::class)->states('contentful')->create();
        $quantity = $this->faker->numberBetween(10, 1000);
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
            'remote_addr'      => $this->faker->ipv4,
            'file'             => UploadedFile::fake()->image('photo.jpg'),
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
                'quantity' => $signup->getQuantity(),
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
     *
     *
     * GET /signups
     * @return void
     */
    public function testSplittingQuantityOnPosts()
    {
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
        $response = $this->withRogueApiKey()->json('POST', 'api/v2/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'quantity'         => 10,
            'caption'          => 'Fake caption',
            'source'           => 'testing',
            'file'             => UploadedFile::fake()->image('photo.jpg'),
        ]);

        // Confirm quantity is persisted between signups and posts correctly.
        $this->assertDatabaseHas('signups', [
            'id' => $signup->id,
            'quantity' => 10,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => 1,
            'quantity' => 10,
        ]);

        // Create another post with a new quantity.
        $response = $this->withRogueApiKey()->json('POST', 'api/v2/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'quantity'         => 20,
            'caption'          => 'Fake caption',
            'source'           => 'testing',
            'file'             => UploadedFile::fake()->image('photo.jpg'),
        ]);

        // Confirm quantity is persisted between signups and posts correctly.
        $this->assertDatabaseHas('signups', [
            'id' => $signup->id,
            'quantity' => 20,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => 1,
            'quantity' => 10,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => 2,
            'quantity' => 10,
        ]);

        // Create another post with a less quantity.
        $response = $this->withRogueApiKey()->json('POST', 'api/v2/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'quantity'         => 5,
            'caption'          => 'Fake caption',
            'source'           => 'testing',
            'file'             => UploadedFile::fake()->image('photo.jpg'),
        ]);

        // Confirm quantity is persisted between signups and posts correctly.
        $this->assertDatabaseHas('signups', [
            'id' => $signup->id,
            'quantity' => 25,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => 1,
            'quantity' => 10,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => 2,
            'quantity' => 10,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => 3,
            'quantity' => 5,
        ]);

        // Create another post with the same quantity as the total.
        $response = $this->withRogueApiKey()->json('POST', 'api/v2/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'quantity'         => 25,
            'caption'          => 'Fake caption',
            'source'           => 'testing',
            'file'             => UploadedFile::fake()->image('photo.jpg'),
        ]);

        // Confirm quantity is persisted between signups and posts correctly.
        $this->assertDatabaseHas('signups', [
            'id' => $signup->id,
            'quantity' => 25,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => 1,
            'quantity' => 10,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => 2,
            'quantity' => 10,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => 3,
            'quantity' => 5,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => 4,
            'quantity' => 0,
        ]);
    }
}
