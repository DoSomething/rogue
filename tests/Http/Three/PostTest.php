<?php

namespace Tests\Http\Three;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\User;
use Rogue\Models\Signup;
use DoSomething\Gateway\Blink;
use Illuminate\Http\UploadedFile;

class PostTest extends TestCase
{
    /**
     * Test that a POST request to /posts creates a new
     * post and signup, if it doesn't already exist.
     *
     * @return void
     */
    public function testCreatingAPostAndSignup()
    {
        $northstarId = $this->faker->northstar_id;
        $campaignId = $this->faker->randomNumber(4);
        $campaignRunId = $this->faker->randomNumber(4);
        $quantity = $this->faker->numberBetween(10, 1000);
        $caption = $this->faker->sentence;

        // Mock the Blink API calls.
        $this->mock(Blink::class)
            ->shouldReceive('userSignup')
            ->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withAccessToken($northstarId, 'admin')->json('POST', 'api/v3/posts', [
            'campaign_id'      => $campaignId,
            'campaign_run_id'  => $campaignRunId,
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
        $response->assertJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'media' => [
                    'url',
                    'original_image_url',
                    'caption',
                ],
                'quantity',
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
        $this->assertDatabaseHas('signups', [
            'campaign_id' => $campaignId,
            'northstar_id' => $northstarId,
        ]);

        $this->assertDatabaseHas('posts', [
            'northstar_id' => $northstarId,
            'campaign_id' => $campaignId,
            'status' => 'pending',
            'quantity' => $quantity,
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
        $response = $this->withAccessToken($signup->northstar_id, 'admin')->postJson('api/v3/posts', [
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
        $response->assertJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'media' => [
                    'url',
                    'original_image_url',
                    'caption',
                ],
                'quantity',
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

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'status' => 'pending',
            'quantity' => $quantity,
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
        $response = $this->postJson('api/v3/posts', [
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

        $response->assertStatus(401);
    }

    /**
     * Test for retrieving all posts.
     *
     * GET /api/v3/posts
     * @return void
     */
    public function testPostsIndex()
    {
        // Anonymous requests should only see accepted posts.
        factory(Post::class, 'accepted', 10)->create();
        factory(Post::class, 'rejected', 5)->create();

        $response = $this->getJson('api/v3/posts');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');

        $response->assertJsonStructure([
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
                    'quantity',
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
                'cursor' => [
                    'current',
                    'prev',
                    'next',
                    'count',
                ],
            ],
        ]);
    }

    /**
     * Test for retrieving all posts as a user.
     *
     * GET /api/v3/posts
     * @return void
     */
    public function testPostsIndexAsUser()
    {
        $userId = $this->faker->northstar_id;

        // The user should only see accepted posts & their own.
        factory(Post::class, 'accepted', 10)->create();
        factory(Post::class, 3)->create(['northstar_id' => $userId]);
        factory(Post::class, 'rejected', 5)->create();

        $response = $this->withAccessToken($userId)->getJson('api/v3/posts');

        $response->assertStatus(200);
        $response->assertJsonCount(13, 'data');
    }

    /**
     * Test for retrieving all posts as an admin.
     *
     * GET /api/v3/posts
     * @return void
     */
    public function testPostsIndexAsAdmin()
    {
        $userId = $this->faker->northstar_id;

        // The admin should be able to see all of these posts!
        factory(Post::class, 'accepted', 10)->create();
        factory(Post::class, 3)->create(['northstar_id' => $userId]);
        factory(Post::class, 'rejected', 5)->create();

        $response = $this->withAccessToken($userId, 'admin')->getJson('api/v3/posts');

        $response->assertStatus(200);
        $response->assertJsonCount(18, 'data');
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
        $response = $this->getJson('api/v3/posts/' . $post->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'media' => [
                    'url',
                    'original_image_url',
                    'caption',
                ],
                'quantity',
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

        $json = $response->json();
        $this->assertEquals($post->id, $json['data']['id']);
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

        $response = $this->withAdminAccessToken()->patchJson('api/v3/posts/' . $post->id, [
            'status' => 'accepted',
            'caption' => 'new caption',
            'quantity' => 8,
        ]);

        $response->assertStatus(200);

        // Make sure that the posts's new status and caption gets persisted in the database.
        $this->assertEquals($post->fresh()->status, 'accepted');
        $this->assertEquals($post->fresh()->caption, 'new caption');
        $this->assertEquals($post->fresh()->quantity, 8);
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

        $response = $this->withAdminAccessToken()->patchJson('api/v3/posts/' . $post->id, [
            'status' => 'approved',
            'caption' => 'This must be longer than 140 characters to break the validation rules so here I will create a caption that is longer than 140 characters to test.',
        ]);

        $response->assertStatus(422);

        $json = $response->json();
        $this->assertEquals('The selected status is invalid.', $json['errors']['status'][0]);
        $this->assertEquals('The caption may not be greater than 140 characters.', $json['errors']['caption'][0]);
    }

    /**
     * Test that a non-staff member or non-admin can't update posts.
     *
     * @return void
     */
    public function testUnAuthorizedUserUpdatingPost()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $response = $this->withAccessToken($user->id)->patchJson('api/v3/posts/' . $post->id, [
            'status' => 'accepted',
            'caption' => 'new caption',
        ]);

        $response->assertStatus(403);

        $json = $response->json();

        $this->assertEquals('You don\'t have the correct role to update this post!', $json['message']);
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

        $response = $this->withAdminAccessToken()->deleteJson('api/v3/posts/' . $post->id);

        $response->assertStatus(200);

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

        $response->assertStatus(401);
    }
}
