<?php

namespace Tests\Http;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\User;
use Rogue\Models\Signup;
use Rogue\Models\Reaction;
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
        $text = $this->faker->sentence;
        $details = ['source-detail' => 'broadcast-123', 'other' => 'other'];

        // Mock the Blink API calls.
        $this->mock(Blink::class)
            ->shouldReceive('userSignup')
            ->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withAccessToken($northstarId)->json('POST', 'api/v3/posts', [
            'campaign_id'      => $campaignId,
            'campaign_run_id'  => $campaignRunId,
            'type'             => 'photo',
            'action'           => 'test-action',
            'quantity'         => $quantity,
            'why_participated' => $this->faker->paragraph,
            'text'             => $text,
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
            'details'          => json_encode($details),
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'type',
                'action',
                'media' => [
                    'url',
                    'original_image_url',
                    'text',
                ],
                'quantity',
                'tags' => [],
                'reactions' => [
                    'reacted',
                    'total',
                ],
                'status',
                'details',
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
            'type' => 'photo',
            'action' => 'test-action',
            'status' => 'pending',
            'quantity' => $quantity,
            'details' => json_encode($details),
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
        $text = $this->faker->sentence;
        $details = ['source-detail' => 'broadcast-123', 'other' => 'other'];

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withAccessToken($signup->northstar_id)->postJson('api/v3/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'type'             => 'photo',
            'action'           => 'test-action',
            'quantity'         => $quantity,
            'why_participated' => $this->faker->paragraph,
            'text'             => $text,
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
            'details'          => json_encode($details),
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'type',
                'action',
                'media' => [
                    'url',
                    'original_image_url',
                    'text',
                ],
                'quantity',
                'tags' => [],
                'reactions' => [
                    'reacted',
                    'total',
                ],
                'status',
                'details',
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
            'type' => 'photo',
            'action' => 'test-action',
            'status' => 'pending',
            'quantity' => $quantity,
            'details' => json_encode($details),
        ]);
    }

    /**
     * Test a post cannot be created without the activity & write scope.
     *
     * @return void
     */
    public function testCreatingAPostWithoutRequiredScopes()
    {
        $signup = factory(Signup::class)->create();
        $quantity = $this->faker->numberBetween(10, 1000);
        $text = $this->faker->sentence;

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Make sure you also need the activity scope.
        $response = $this->postJson('api/v3/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'type'             => 'photo',
            'action'           => 'test-action',
            'quantity'         => $quantity,
            'why_participated' => $this->faker->paragraph,
            'text'             => $text,
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
        ]);

        $response->assertStatus(401);
        $this->assertEquals('Unauthenticated.', $response->decodeResponseJson()['message']);
    }

    /**
     * Test that a POST request to /posts with an existing post creates an additional new photo post.
     *
     * @return void
     */
    public function testCreatingMultiplePosts()
    {
        $signup = factory(Signup::class)->create();
        $quantity = $this->faker->numberBetween(10, 1000);
        $text = $this->faker->sentence;
        $details = ['source-detail' => 'broadcast-123', 'other' => 'other'];

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withAccessToken($signup->northstar_id)->postJson('api/v3/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'type'             => 'photo',
            'action'           => 'test-action',
            'quantity'         => $quantity,
            'why_participated' => $this->faker->paragraph,
            'text'             => $text,
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
            'details'          => json_encode($details),
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'type',
                'action',
                'media' => [
                    'url',
                    'original_image_url',
                    'text',
                ],
                'quantity',
                'tags' => [],
                'reactions' => [
                    'reacted',
                    'total',
                ],
                'status',
                'details',
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
            'type' => 'photo',
            'action' => 'test-action',
            'status' => 'pending',
            'quantity' => $quantity,
            'details' => json_encode($details),
        ]);

        // Create a second post without why_participated.
        $secondQuantity = $this->faker->numberBetween(10, 1000);
        $secondText = $this->faker->sentence;
        $secondDetails = ['source-detail' => 'broadcast-333', 'other' => 'other'];

        $response = $this->withAccessToken($signup->northstar_id)->postJson('api/v3/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'type'             => 'photo',
            'action'           => 'test-action',
            'quantity'         => $secondQuantity,
            'text'             => $secondText,
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
            'details'          => json_encode($secondDetails),
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'type',
                'action',
                'media' => [
                    'url',
                    'original_image_url',
                    'text',
                ],
                'quantity',
                'tags' => [],
                'reactions' => [
                    'reacted',
                    'total',
                ],
                'status',
                'details',
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
            'type' => 'photo',
            'action' => 'test-action',
            'status' => 'pending',
            'quantity' => $secondQuantity,
            'details' => json_encode($secondDetails),
        ]);

        // Assert that signup quantity is sum of all posts' quantities.
        $this->assertEquals($signup->fresh()->quantity, $quantity + $secondQuantity);
    }

    /**
     * Test that a POST request to /posts with `null` as the quantity creates a new post.
     *
     * @return void
     */
    public function testCreatingAPostWithNullAsQuantity()
    {
        $signup = factory(Signup::class)->create();
        $text = $this->faker->sentence;
        $details = ['source-detail' => 'broadcast-123', 'other' => 'other'];

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withAccessToken($signup->northstar_id, 'user')->postJson('api/v3/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'type'             => 'photo',
            'action'           => 'test-action',
            'quantity'         => null,
            'text'             => $text,
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
            'details'          => json_encode($details),
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'type',
                'action',
                'media' => [
                    'url',
                    'original_image_url',
                    'text',
                ],
                'quantity',
                'tags' => [],
                'reactions' => [
                    'reacted',
                    'total',
                ],
                'status',
                'details',
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
            'type' => 'photo',
            'action' => 'test-action',
            'status' => 'pending',
            'quantity' => null,
            'details' => json_encode($details),
        ]);
    }

    /**
     * Test that a POST request to /posts without a quantity param creates a new post.
     *
     * @return void
     */
    public function testCreatingAPostWithoutQuantityParam()
    {
        $signup = factory(Signup::class)->create();
        $text = $this->faker->sentence;

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withAccessToken($signup->northstar_id)->postJson('api/v3/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'type'             => 'photo',
            'action'           => 'test-action',
            'text'             => $text,
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'type',
                'action',
                'media' => [
                    'url',
                    'original_image_url',
                    'text',
                ],
                'quantity',
                'tags' => [],
                'reactions' => [
                    'reacted',
                    'total',
                ],
                'status',
                'details',
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
            'type' => 'photo',
            'action' => 'test-action',
            'status' => 'pending',
            'quantity' => null,
            'details' => null,
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
        $text = $this->faker->sentence;

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->postJson('api/v3/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'campaign_run_id'  => $signup->campaign_run_id,
            'type'             => 'photo',
            'action'           => 'test-action',
            'quantity'         => $quantity,
            'why_participated' => $this->faker->paragraph,
            'text'             => $text,
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test for retrieving all posts as non-admin and non-owner.
     * Non-admins/non-owners should not see tags, source, and remote_addr.
     *
     * GET /api/v3/posts
     * @return void
     */
    public function testPostsIndexAsNonAdminNonOwner()
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
                        'text',
                    ],
                    'quantity',
                    'reactions' => [
                        'reacted',
                        'total',
                    ],
                    'status',
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
     * Test for retrieving all posts as admin
     * Admins should see tags, source, and remote_addr.
     *
     * GET /api/v3/posts
     * @return void
     */
    public function testPostsIndexAsAdmin()
    {
        // Admins should see all posts.
        factory(Post::class, 'accepted', 10)->create();
        factory(Post::class, 'rejected', 5)->create();

        $response = $this->withAdminAccessToken()->getJson('api/v3/posts');

        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'signup_id',
                    'northstar_id',
                    'type',
                    'action',
                    'media' => [
                        'url',
                        'original_image_url',
                        'text',
                    ],
                    'quantity',
                    'reactions' => [
                        'reacted',
                        'total',
                    ],
                    'status',
                    'created_at',
                    'updated_at',
                    'tags' => [],
                    'source',
                    'details',
                    'remote_addr',
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
     * Test for retrieving all posts as owner.
     * Owners should see tags, source, and remote_addr.
     *
     * GET /api/v3/posts
     * @return void
     */
    public function testPostsIndexAsOwner()
    {
        // Owners should only see accepted posts and their own pending/rejected posts.
        $posts = factory(Post::class, 2)->create();
        $rejectedPosts = factory(Post::class, 'rejected', 3)->create();

        $northstarId = $this->faker->northstar_id;

        foreach ($posts as $post) {
            $post->northstar_id = $northstarId;
            $post->save();
        }

        foreach ($rejectedPosts as $rejectedPost) {
            $rejectedPost->northstar_id = '59ea1c1ca0bfad5e90139dcz';
            $rejectedPost->save();
        }

        $response = $this->withAccessToken($northstarId)->getJson('api/v3/posts');
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'signup_id',
                    'northstar_id',
                    'type',
                    'action',
                    'media' => [
                        'url',
                        'original_image_url',
                        'text',
                    ],
                    'quantity',
                    'reactions' => [
                        'reacted',
                        'total',
                    ],
                    'status',
                    'created_at',
                    'updated_at',
                    'tags' => [],
                    'source',
                    'details',
                    'remote_addr',
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
     * Test for retrieving a specific post as non-admin and non-owner.
     *
     * GET /api/v3/post/:post_id
     * @return void
     */
    public function testPostShowAsNonAdminNonOwner()
    {
        // Anon user should not be able to see a pending post if it doesn't belong to them and if they're not an admin.
        $post = factory(Post::class)->create();
        $response = $this->getJson('api/v3/posts/' . $post->id);

        $response->assertStatus(403);

        // Anon user should not be able to see a rejected post if it doesn't belong to them and if they're not an admin.
        $post = factory(Post::class, 'rejected')->create();
        $response = $this->getJson('api/v3/posts/' . $post->id);

        $response->assertStatus(403);

        // Anon user is able to see an accepted post even if it doesn't belong to them and if they're not an admin.
        $post = factory(Post::class, 'accepted')->create();
        $response = $this->getJson('api/v3/posts/' . $post->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'type',
                'action',
                'media' => [
                    'url',
                    'original_image_url',
                    'text',
                ],
                'quantity',
                'reactions' => [
                    'reacted',
                    'total',
                ],
                'status',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    /**
     * Test for retrieving a specific post as admin.
     *
     * GET /api/v3/post/:post_id
     * @return void
     */
    public function testPostShowAsAdmin()
    {
        $post = factory(Post::class)->create();
        $response = $this->withAdminAccessToken()->getJson('api/v3/posts/' . $post->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'type',
                'action',
                'media' => [
                    'url',
                    'original_image_url',
                    'text',
                ],
                'quantity',
                'reactions' => [
                    'reacted',
                    'total',
                ],
                'status',
                'created_at',
                'updated_at',
                'tags' => [],
                'source',
                'details',
                'remote_addr',
            ],
        ]);

        $json = $response->json();
        $this->assertEquals($post->id, $json['data']['id']);
    }

    /**
     * Test for retrieving a specific post as owner.
     *
     * GET /api/v3/post/:post_id
     * @return void
     */
    public function testPostShowAsOwner()
    {
        $post = factory(Post::class)->create();
        $response = $this->withAccessToken($post->northstar_id)->getJson('api/v3/posts/' . $post->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'type',
                'action',
                'media' => [
                    'url',
                    'original_image_url',
                    'text',
                ],
                'quantity',
                'reactions' => [
                    'reacted',
                    'total',
                ],
                'status',
                'created_at',
                'updated_at',
                'tags' => [],
                'source',
                'details',
                'remote_addr',
            ],
        ]);

        $json = $response->json();
        $this->assertEquals($post->id, $json['data']['id']);
    }

    /**
     * Test for retrieving a specific post with reactions.
     *
     * GET /api/v3/post/:post_id
     * @return void
     */
    public function testPostShowWithReactions()
    {
        $viewer = $this->randomUserId();
        $post = factory(Post::class, 'accepted')->create();

        // Create two reactions for this post!
        Reaction::withTrashed()->firstOrCreate(['northstar_id' => $viewer, 'post_id' => $post->id]);
        Reaction::withTrashed()->firstOrCreate(['northstar_id' => 'someone_else_lol', 'post_id' => $post->id]);

        $response = $this->withAccessToken($viewer, 'user')->getJson('api/v3/posts/' . $post->id);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $post->id,
                'reactions' => [
                    'reacted' => true,
                    'total' => 2,
                ],
            ],
        ]);
    }

    /**
     * Test for updating a post successfully.
     *
     * PATCH /api/v3/posts/186
     * @return void
     */
    public function testUpdatingAPhotoPost()
    {
        $post = factory(Post::class)->create();
        $signup = $post->signup;

        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        $response = $this->withAdminAccessToken()->patchJson('api/v3/posts/' . $post->id, [
            'text' => 'new caption',
            'quantity' => 8,
            'status' => 'accepted'
        ]);

        $response->assertStatus(200);

        // Make sure that the posts's new status and text gets persisted in the database.
        $this->assertEquals($post->fresh()->text, 'new caption');
        $this->assertEquals($post->fresh()->quantity, 8);

        // Make sure the signup's quantity gets updated.
        $this->assertEquals($signup->fresh()->quantity, 8);
    }

    /**
     * Test for updating a post successfully.
     *
     * PATCH /api/v3/posts/186
     * @return void
     */
    public function testUpdatingAPhotoWithBadStatus()
    {
        $post = factory(Post::class)->create();
        $signup = $post->signup;

        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        $response = $this->withAdminAccessToken()->patchJson('api/v3/posts/' . $post->id, [
            'text' => 'new caption',
            'quantity' => 8,
            'status' => 'register-form'
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test for updating a post without activity scope.
     *
     * PATCH /api/v3/posts/186
     * @return void
     */
    public function testUpdatingAPostWithoutActivityScope()
    {
        $post = factory(Post::class)->create();
        $signup = $post->signup;

        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        $response = $this->patchJson('api/v3/posts/' . $post->id, [
            'text' => 'new caption',
            'quantity' => 8,
        ]);

        $response->assertStatus(401);
        $this->assertEquals('Unauthenticated.', $response->decodeResponseJson()['message']);
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
            'quantity' => 'this is words not a number!',
            'text' => 'This must be longer than 140 characters to break the validation rules so here I will create a caption that is longer than 140 characters to test.',
        ]);

        $response->assertStatus(422);

        $json = $response->json();
        $this->assertEquals('The quantity must be an integer.', $json['errors']['quantity'][0]);
        $this->assertEquals('The text may not be greater than 140 characters.', $json['errors']['text'][0]);
    }

    /**
     * Test that a non-admin or user that doesn't own the post can't update post.
     *
     * @return void
     */
    public function testUnAuthorizedUserUpdatingPost()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();

        $response = $this->withAccessToken($user->id)->patchJson('api/v3/posts/' . $post->id, [
            'status' => 'accepted',
            'text' => 'new caption',
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
     * Test deleteing a post without the activity scope.
     *
     * @return void
     */
    public function testDeletingAPostWithoutActivityScope()
    {
        $post = factory(Post::class)->create();

        $response = $this->deleteJson('api/v3/posts/' . $post->id);

        $response->assertStatus(401);
        $this->assertEquals('Unauthenticated.', $response->decodeResponseJson()['message']);
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

    /**
     * Test that only non-staff users can see their own unapproved posts.
     *
     * @return void
     */
    public function testAuthorizationLogicForUnapprovedPostsByOwner()
    {
        $post = factory(Post::class)->create();

        $response = $this->withAccessToken($post->northstar_id)->getJson('api/v3/posts/' . $post->id);

        $response->assertStatus(200);

        $json = $response->json();

        $this->assertEquals('pending', $json['data']['status']);
    }

    /**
     * Test that users can't see other's unapproved posts.
     *
     * @return void
     */
    public function testAuthorizationLogicForUnapprovedPostsByNonOwner()
    {
        $post = factory(Post::class)->create();

        $response = $this->getJson('api/v3/posts/' . $post->id);

        $response->assertStatus(403);

        $json = $response->json();

        $this->assertEquals('You don\'t have the correct role to view this post!', $json['message']);
    }

    /**
     * Test that admins can see unapproved posts.
     *
     * @return void
     */
    public function testAuthorizationLogicForUnapprovedPostsForAdmin()
    {
        $post = factory(Post::class)->create();

        $response = $this->withAdminAccessToken()->getJson('api/v3/posts/' . $post->id);

        $response->assertStatus(200);

        $json = $response->json();

        $this->assertEquals('pending', $json['data']['status']);
    }

    /**
     * Test creating voter-reg post
     *
     * @return void
     */
    public function testCreatingVoterRegistrationPost()
    {
        $signup = factory(Signup::class)->create();

        $details = [
            "hostname" => "dosomething.turbovote.org",
            "referral-code" => "user:5570af2c469c6430068bc501,campaign:8022,source:web",
            "partner-comms-opt-in" => "",
            "created-at" => "2018-01-29T01:59:44Z",
            "updated-at" => "2018-01-29T02:00:17Z",
            "voter-registration-status" => "initiated",
            "voter-registration-source" => "turbovote",
            "voter-registration-method" => "by-mail",
            "voting-method-preference" => "in-person",
            "email subscribed" => "FALSE",
            "sms subscribed" => "TRUE"
        ];

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withAdminAccessToken()->postJson('api/v3/posts', [
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'campaign_run_id' => $signup->campaign_run_id,
            'type' => 'voter-reg',
            'action' => 'test-action',
            'status' => 'register-form',
            'details' => json_encode($details),
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'type',
                'action',
                'media' => [
                    'url',
                    'original_image_url',
                    'text',
                ],
                'quantity',
                'tags' => [],
                'reactions' => [
                    'reacted',
                    'total',
                ],
                'status',
                'details',
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
            'type' => 'voter-reg',
            'action' => 'test-action',
            'status' => 'register-form',
            'details' => json_encode($details),
        ]);
    }
}
