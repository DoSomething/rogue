<?php

namespace Tests\Http\Three;

use Rogue\Models\Post;
use Rogue\Models\Signup;
use Tests\BrowserKitTestCase;
use Illuminate\Http\UploadedFile;
use DoSomething\Gateway\Blink;

class PostTest extends BrowserKitTestCase
{
    /**
     * Test that a POST request to /posts creates a new
     * post and signup, if it doesn't already exist.
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
        $response = $this->withRogueApiKey()->json('POST', 'api/v3/posts', [
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

        $response->assertResponseStatus(200);
        $response->seeJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'media' => [
                    'url',
                    'original_image_url',
                    'caption',
                ],
                'tags' => [],
                'reactions' => [
                    'reacted',
                    'total',
                ],
                'status',
                'source',
                'remote_addr',
                'created_at',
                'updated_at',
            ],
        ]);

        // Make sure the signup & post are persisted to the database.
        $this->seeInDatabase('signups', [
            'campaign_id' => $campaign_id,
            'northstar_id' => $northstar_id,
            'quantity' => $quantity,
        ]);

        $this->seeInDatabase('posts', [
            'northstar_id' => $northstar_id,
            'campaign_id' => $campaign_id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test that a POST request to /posts creates a new post.
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
        $response = $this->withRogueApiKey()->json('POST', 'api/v3/posts', [
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

        $response->assertResponseStatus(200);

        $response->seeJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'media' => [
                    'url',
                    'original_image_url',
                    'caption',
                ],
                'tags' => [],
                'reactions' => [
                    'reacted',
                    'total',
                ],
                'status',
                'source',
                'remote_addr',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->seeInDatabase('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test that non-authenticated user's/apps can't create a post.
     *
     * @return void
     */
    public function testUnauthenticatedUserCreatingAPost()
    {
        $signup = factory(Signup::class)->create();
        $quantity = $this->faker->numberBetween(10, 1000);
        $caption = $this->faker->sentence;

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->json('POST', 'api/v3/posts', [
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

        $response->assertResponseStatus(401);
    }

    /**
     * Test for retrieving all posts.
     *
     * GET /api/v3/posts
     * @return void
     */
    public function testPostsIndex()
    {
        factory(Post::class, 10)->create();

        $this->get('api/v3/posts');

        $this->assertResponseStatus(200);
        $this->seeJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'signup_id',
                    'northstar_id',
                    'media' => [
                        'url',
                        'original_image_url',
                        'caption',
                    ],
                    'tags' => [],
                    'reactions' => [
                        'reacted',
                        'total',
                    ],
                    'status',
                    'source',
                    'remote_addr',
                    'created_at',
                    'updated_at',
                ],
            ],
            'meta' => [
                'pagination' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages',
                    'links',
                ],
            ],
        ]);
    }

    /**
     * Test for retrieving a specific post.
     *
     * GET /api/v3/post/:post_id
     * @return void
     */
    public function testPostShow()
    {
        $post = factory(Post::class)->create();
        $response = $this->get('api/v3/posts/' . $post->id);

        $this->assertResponseStatus(200);
        $this->seeJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'media' => [
                    'url',
                    'original_image_url',
                    'caption',
                ],
                'tags' => [],
                'reactions' => [
                    'reacted',
                    'total',
                ],
                'status',
                'source',
                'remote_addr',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->assertEquals($post->id, $this->response->getOriginalContent()['data']['id']);
    }

    /**
     * Test for updating a post successfully.
     *
     * PATCH /api/v3/posts/186
     * @return void
     */
    public function testUpdatingAPost()
    {
        $post = factory(Post::class)->create();

        $response = $this->withRogueApiKey()->json('PATCH', 'api/v3/posts/' . $post->id, [
            'status' => 'accepted',
            'caption' => 'new caption',
        ]);

        $this->assertResponseStatus(200);

        // Make sure that the posts's new status and caption gets persisted in the database.
        $this->assertEquals($post->fresh()->status, 'accepted');
        $this->assertEquals($post->fresh()->caption, 'new caption');
    }

    /**
     * Test validation for updating a post.
     *
     * PATCH /api/v3/posts/195
     * @return void
     */
    public function testValidationUpdatingAPost()
    {
        $post = factory(Post::class)->create();

        $response = $this->withRogueApiKey()->json('PATCH', 'api/v3/posts/' . $post->id, [
            'status' => 'approved',
            'caption' => 'This must be longer than 140 characters to break the validation rules so here I will create a caption that is longer than 140 characters to test.',
        ]);

        $this->assertResponseStatus(422);

        $this->assertEquals('The selected status is invalid.', $this->response->getOriginalContent()['status'][0]);

        $this->assertEquals('The caption may not be greater than 140 characters.', $this->response->getOriginalContent()['caption'][0]);
    }

    /**
     * Test that non-authenticated user's/apps can't update posts.
     *
     * @return void
     */
    public function testUnauthenticatedUserUpdatingAPost()
    {
        $post = factory(Post::class)->create();

        $response = $this->json('PATCH', 'api/v3/posts/' . $post->id, [
            'status' => 'accepted',
            'caption' => 'new caption',
        ]);

        $response->assertResponseStatus(401);
    }

    /**
     * Test that a post gets deleted when hitting the DELETE endpoint.
     *
     * @return void
     */
    public function testDeletingAPost()
    {
        $post = factory(Post::class)->create();

        // Mock time of when the post is soft deleted.
        $this->mockTime('8/3/2017 14:00:00');

        $response = $this->withRogueApiKey()->delete('api/v3/posts/' . $post->id);

        $this->assertResponseStatus(200);

        // Make sure that the post's deleted_at gets persisted in the database.
        $this->assertEquals($post->fresh()->deleted_at->toTimeString(), '14:00:00');
    }

    /**
     * Test that non-authenticated user's/apps can't delete posts.
     *
     * @return void
     */
    public function testUnauthenticatedUserDeletingAPost()
    {
        $post = factory(Post::class)->create();

        $response = $this->deleteJson('api/v3/posts/' . $post->id);

        $response->assertResponseStatus(401);
    }
}
