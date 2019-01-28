<?php

namespace Tests\Http;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\User;
use Rogue\Models\Action;
use Rogue\Models\Signup;
use Rogue\Models\Campaign;
use Rogue\Models\Reaction;
use Rogue\Services\Fastly;
use DoSomething\Gateway\Blink;
use Illuminate\Http\UploadedFile;

class PostTest extends TestCase
{
    /**
     * Helper function to assert post structure
     */
    public function assertPostStructure($response)
    {
        return $response->assertJsonStructure([
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
    }

    /**
     * Test that a POST request to /posts creates a new
     * post and signup, if it doesn't already exist.
     *
     * @return void
     */
    public function testCreatingAPostAndSignup()
    {
        $northstarId = $this->faker->northstar_id;
        $campaignId = factory(Campaign::class)->create()->id;
        $quantity = $this->faker->numberBetween(10, 1000);
        $why_participated = $this->faker->paragraph;
        $text = $this->faker->sentence;
        $details = ['source-detail' => 'broadcast-123', 'other' => 'other'];

        // Create an action to refer to.
        $action = factory(Action::class)->create(['campaign_id' => $campaignId]);

        // Mock the Blink API calls.
        $this->mock(Blink::class)
            ->shouldReceive('userSignup')
            ->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withAccessToken($northstarId)->json('POST', 'api/v3/posts', [
            'campaign_id'      => $campaignId,
            'type'             => 'photo',
            'action'           => 'test-action',
            'action_id'        => $action->id,
            'quantity'         => $quantity,
            'why_participated' => $why_participated,
            'text'             => $text,
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
            'details'          => json_encode($details),
        ]);

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        // Make sure the signup & post are persisted to the database.
        $this->assertDatabaseHas('signups', [
            'campaign_id' => $campaignId,
            'northstar_id' => $northstarId,
            'why_participated' => $why_participated,
        ]);

        $this->assertDatabaseHas('posts', [
            'northstar_id' => $northstarId,
            'campaign_id' => $campaignId,
            'type' => 'photo',
            'action' => 'test-action',
            'action_id' => $action->id,
            'status' => 'pending',
            'quantity' => $quantity,
            'details' => json_encode($details),
        ]);
    }

    /**
     * Test that a POST request to /posts creates a new photo post.
     *
     * @return void
     */
    public function testCreatingAPhotoPost()
    {
        $signup = factory(Signup::class)->create();
        $quantity = $this->faker->numberBetween(10, 1000);
        $why_participated = $this->faker->paragraph;
        $text = $this->faker->sentence;
        $details = ['source-detail' => 'broadcast-123', 'other' => 'other'];
        $action = factory(Action::class)->create(['campaign_id' => $signup->campaign_id]);

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withAccessToken($signup->northstar_id)->postJson('api/v3/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'type'             => 'photo',
            'action'           => 'test-action',
            'action_id'        => $action->id,
            'quantity'         => $quantity,
            'why_participated' => $why_participated,
            'text'             => $text,
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
            'details'          => json_encode($details),
        ]);

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => 'photo',
            'action' => 'test-action',
            'action_id' => $action->id,
            'status' => 'pending',
            'quantity' => $quantity,
            'details' => json_encode($details),
        ]);

        // Make sure the updated why_participated is updated on the signup.
        $this->assertDatabaseHas('signups', [
            'campaign_id' => $signup->campaign_id,
            'northstar_id' => $signup->northstar_id,
            'why_participated' => $why_participated,
        ]);
    }

    /**
     * Test that a POST request to /posts creates a new text post.
     *
     * @return void
     */
    public function testCreatingATextPost()
    {
        $signup = factory(Signup::class)->create();
        $quantity = $this->faker->numberBetween(10, 1000);
        $text = $this->faker->sentence;
        $why_participated = $this->faker->paragraph;
        $details = ['source-detail' => 'broadcast-123', 'other' => 'other'];
        $action = factory(Action::class)->create(['campaign_id' => $signup->campaign_id]);

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withAccessToken($signup->northstar_id)->postJson('api/v3/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'type'             => 'text',
            'action'           => 'test-action',
            'action_id'        => $action->id,
            'quantity'         => $quantity,
            'why_participated' => $why_participated,
            'text'             => $text,
            'details'          => json_encode($details),
        ]);

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => 'text',
            'action' => 'test-action',
            'action_id' => $action->id,
            'status' => 'pending',
            'quantity' => $quantity,
            'details' => json_encode($details),
        ]);

        // Make sure the updated why_participated is updated on the signup.
        $this->assertDatabaseHas('signups', [
            'campaign_id' => $signup->campaign_id,
            'northstar_id' => $signup->northstar_id,
            'why_participated' => $why_participated,
        ]);
    }

    /**
     * Test that a POST request to /posts creates a new voter-reg post.
     *
     * @return void
     */
    public function testCreatingAVoterRegPost()
    {
        $signup = factory(Signup::class)->create();
        $details = ['source-detail' => 'broadcast-123', 'other' => 'other'];
        $action = factory(Action::class)->create(['campaign_id' => $signup->campaign_id]);

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withAccessToken($signup->northstar_id)->postJson('api/v3/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'type'             => 'voter-reg',
            'action'           => 'test-action',
            'action_id'        => $action->id,
            'quantity'         => null,
            'text'             => null,
            'details'          => json_encode($details),
        ]);

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => 'voter-reg',
            'action' => 'test-action',
            'action_id' => $action->id,
            'status' => 'pending',
            'details' => json_encode($details),
        ]);
    }

    /**
     * Test that a POST request to /posts creates a new share-social post.
     *
     * @return void
     */
    public function testCreatingAShareSocialPost()
    {
        $signup = factory(Signup::class)->create();
        $quantity = $this->faker->numberBetween(10, 1000);
        $text = $this->faker->sentence;
        $details = ['source-detail' => 'broadcast-123', 'other' => 'other'];
        $action = factory(Action::class)->create(['campaign_id' => $signup->campaign_id]);

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withAccessToken($signup->northstar_id)->postJson('api/v3/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'type'             => 'share-social',
            'action'           => 'test-action',
            'action_id'        => $action->id,
            'quantity'         => $quantity,
            'text'             => $text,
            'details'          => json_encode($details),
        ]);

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => 'share-social',
            'action' => 'test-action',
            'action_id' => $action->id,
            // Social share posts should be auto-accepted (unless an admin sends a custom status).
            'status' => 'accepted',
            'details' => json_encode($details),
        ]);
    }

    /**
     * Test that a POST request to /posts as an admin creates a new auto-accepted share-social post.
     *
     * @return void
     */
    public function testCreatingAShareSocialPostAsAdmin()
    {
        $signup = factory(Signup::class)->create();
        $quantity = $this->faker->numberBetween(10, 1000);
        $text = $this->faker->sentence;
        $details = ['source-detail' => 'broadcast-123', 'other' => 'other'];
        $action = factory(Action::class)->create(['campaign_id' => $signup->campaign_id]);

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withAdminAccessToken()->postJson('api/v3/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'type'             => 'share-social',
            'action'           => 'test-action',
            'action_id'        => $action->id,
            'quantity'         => $quantity,
            'text'             => $text,
            'details'          => json_encode($details),
        ]);

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => 'share-social',
            'action' => 'test-action',
            'action_id' => $action->id,
            // Social share posts should be auto-accepted (unless an admin sends a custom status).
            'status' => 'accepted',
            'details' => json_encode($details),
        ]);
    }

    /**
     * Test that a POST request to /posts as an admin with a custom status creates a new share-social post that is pending.
     *
     * @return void
     */
    public function testCreatingAShareSocialPostAsAdminWithCustomStatus()
    {
        $signup = factory(Signup::class)->create();
        $quantity = $this->faker->numberBetween(10, 1000);
        $text = $this->faker->sentence;
        $details = ['source-detail' => 'broadcast-123', 'other' => 'other'];
        $action = factory(Action::class)->create(['campaign_id' => $signup->campaign_id]);

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withAdminAccessToken()->postJson('api/v3/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'type'             => 'share-social',
            'action'           => 'test-action',
            'action_id'        => $action->id,
            'quantity'         => $quantity,
            'text'             => $text,
            'details'          => json_encode($details),
            'status'           => 'pending',
        ]);

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => 'share-social',
            'action' => 'test-action',
            'action_id' => $action->id,
            // Social share posts should be pending since the admin sent a custom status.
            'status' => 'pending',
            'details' => json_encode($details),
        ]);
    }

    /**
     * Test a post cannot be created that is not one of the following types: text, photo, voter-reg, share-social.
     *
     * @return void
     */
    public function testCreatingAPostWithoutValidTypeScopes()
    {
        $signup = factory(Signup::class)->create();
        $quantity = $this->faker->numberBetween(10, 1000);
        $text = $this->faker->sentence;
        $details = ['source-detail' => 'broadcast-123', 'other' => 'other'];

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post with an invalid type (not in text, photo, voter-reg, share-social).
        $response = $this->withAccessToken($signup->northstar_id)->postJson('api/v3/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'type'             => 'social-share',
            'action'           => 'test-action',
            'quantity'         => $quantity,
            'text'             => $text,
            'details'          => json_encode($details),
        ]);

        $response->assertStatus(422);
        $this->assertEquals('The selected type is invalid.', $response->decodeResponseJson()['errors']['type'][0]);
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
        $action = factory(Action::class)->create(['campaign_id' => $signup->campaign_id]);

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withAccessToken($signup->northstar_id)->postJson('api/v3/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'type'             => 'photo',
            'action'           => 'test-action',
            'action_id'        => $action->id,
            'quantity'         => $quantity,
            'why_participated' => $this->faker->paragraph,
            'text'             => $text,
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
            'details'          => json_encode($details),
        ]);

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => 'photo',
            'action' => 'test-action',
            'action_id' => $action->id,
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
            'type'             => 'photo',
            'action'           => 'test-action',
            'action_id'        => $action->id,
            'quantity'         => $secondQuantity,
            'text'             => $secondText,
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
            'details'          => json_encode($secondDetails),
        ]);

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => 'photo',
            'action' => 'test-action',
            'action_id' => $action->id,
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
        $action = factory(Action::class)->create(['campaign_id' => $signup->campaign_id]);

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withAccessToken($signup->northstar_id, 'user')->postJson('api/v3/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'type'             => 'photo',
            'action'           => 'test-action',
            'action_id'        => $action->id,
            'quantity'         => null,
            'text'             => $text,
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
            'details'          => json_encode($details),
        ]);

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => 'photo',
            'action' => 'test-action',
            'action_id' => $action->id,
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
        $action = factory(Action::class)->create(['campaign_id' => $signup->campaign_id]);

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withAccessToken($signup->northstar_id)->postJson('api/v3/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'type'             => 'photo',
            'action'           => 'test-action',
            'action_id'        => $action->id,
            'text'             => $text,
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
        ]);

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => 'photo',
            'action' => 'test-action',
            'action_id' => $action->id,
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
     * Test creating a post without sending an action_id
     *
     * @return void
     */
    public function testCreatingAPostWithoutSendingActionId()
    {
        $signup = factory(Signup::class)->create();
        $text = $this->faker->sentence;
        $quantity = $this->faker->numberBetween(10, 1000);
        $action = factory(Action::class)->create([
            'campaign_id' => $signup->campaign_id,
            'name' => 'test-action',
            'post_type' => 'photo',
        ]);

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post without sending an action_id!
        $response = $this->withAccessToken($signup->northstar_id)->postJson('api/v3/posts', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'type'             => 'photo',
            'action'           => 'test-action',
            'quantity'         => $quantity,
            'why_participated' => $this->faker->paragraph,
            'text'             => $text,
            'file'             => UploadedFile::fake()->image('photo.jpg', 450, 450),
        ]);

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => 'photo',
            'action' => 'test-action',
            'action_id' => $action->id,
            'status' => 'pending',
            'quantity' => $quantity,
        ]);
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
     * Test for retrieving all posts as non-admin and non-owner.
     * Posts tagged as "Hide In Gallery" should not be returned to Non-admins/non-owners.
     *
     * GET /api/v3/posts
     * @return void
     */
    public function testPostsIndexAsNonAdminNonOwnerHiddenPosts()
    {
        // Anonymous requests should only see posts that are not tagged with "Hide In Gallery."
        factory(Post::class, 'accepted', 10)->create();

        $hiddenPost = factory(Post::class, 'accepted')->create();
        $hiddenPost->tag('Hide In Gallery');

        $response = $this->getJson('api/v3/posts');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
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
     * Test for retrieving all posts as admin
     * Admins should see hidden posts.
     *
     * GET /api/v3/posts
     * @return void
     */
    public function testPostsIndexAsAdminHiddenPosts()
    {
        // Admins should see all posts.
        factory(Post::class, 'accepted', 10)->create();

        $hiddenPost = factory(Post::class, 'accepted')->create();
        $hiddenPost->tag('Hide In Gallery');

        $response = $this->withAdminAccessToken()->getJson('api/v3/posts');
        $response->assertStatus(200);
        $response->assertJsonCount(11, 'data');
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
        $userId = $this->faker->unique()->northstar_id;
        factory(Post::class, 2)->create(['northstar_id' => $userId]);
        factory(Post::class, 'rejected', 1)->create(['northstar_id' => $userId]);

        $otherId = $this->faker->unique()->northstar_id;
        factory(Post::class, 'rejected', 4)->create(['northstar_id' => $otherId]);

        // Owners should be able to see their own posts of any status, but
        // not pending or rejected posts from other users.
        $response = $this->withAccessToken($userId)->getJson('api/v3/posts');

        $response->assertSuccessful();
        $response->assertJsonCount(3, 'data');
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
     * Owners should see their own hidden posts.
     *
     * GET /api/v3/posts
     * @return void
     */
    public function testPostsIndexAsOwnerHiddenPosts()
    {
        // Owners should only see accepted posts and their own hidden posts.
        $ownerId = $this->faker->northstar_id;

        // Create posts and associate to this $ownerId.
        $posts = factory(Post::class, 'accepted', 2)->create(['northstar_id' => $ownerId]);

        // Create a hidden post from the same $ownerId.
        $hiddenPost = factory(Post::class, 'accepted')->create(['northstar_id' => $ownerId]);
        $hiddenPost->tag('Hide In Gallery');
        $hiddenPost->save();

        // Create anothter hidden post by different user.
        $secondHiddenPost = factory(Post::class, 'accepted')->create([
            'northstar_id' => $this->faker->unique()->northstar_id,
        ]);
        $secondHiddenPost->tag('Hide In Gallery');
        $secondHiddenPost->save();

        $response = $this->withAccessToken($ownerId)->getJson('api/v3/posts');
        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    /**
     * Test for retrieving a specific post as non-admin and non-owner.
     * Non-admin and non-owners can't see other's unapproved or any rejected posts.
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
     * Admins can see all posts (unapproved, rejected, and post that don't belong to them).
     *
     * GET /api/v3/post/:post_id
     * @return void
     */
    public function testPostShowAsAdmin()
    {
        $post = factory(Post::class)->create();
        $response = $this->withAdminAccessToken()->getJson('api/v3/posts/' . $post->id);

        $response->assertStatus(200);
        $this->assertPostStructure($response);

        $json = $response->json();
        $this->assertEquals($post->id, $json['data']['id']);
    }

    /**
     * Test for retrieving a specific post as owner (and only owners can see their own unapproved posts).
     *
     * GET /api/v3/post/:post_id
     * @return void
     */
    public function testPostShowAsOwner()
    {
        $post = factory(Post::class)->create();
        $response = $this->withAccessToken($post->northstar_id)->getJson('api/v3/posts/' . $post->id);

        $response->assertStatus(200);
        // $this->assertPostStructure($response);

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
            'status' => 'accepted',
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
            'status' => 'register-form',
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

        $this->assertEquals('This action is unauthorized.', $json['message']);
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

        // Mock the Fastly API calls.
        $this->mock(Fastly::class)
            ->shouldReceive('purgeKey');

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
     * Test creating voter-reg post
     *
     * @return void
     */
    public function testCreatingVoterRegistrationPost()
    {
        $signup = factory(Signup::class)->create();
        $action = factory(Action::class)->create(['campaign_id' => $signup->campaign_id]);

        $details = [
            'hostname' => 'dosomething.turbovote.org',
            'referral-code' => 'user:5570af2c469c6430068bc501,campaign:8022,source:web',
            'partner-comms-opt-in' => '',
            'created-at' => '2018-01-29T01:59:44Z',
            'updated-at' => '2018-01-29T02:00:17Z',
            'voter-registration-status' => 'initiated',
            'voter-registration-source' => 'turbovote',
            'voter-registration-method' => 'by-mail',
            'voting-method-preference' => 'in-person',
            'email subscribed' => 'FALSE',
            'sms subscribed' => 'TRUE',
        ];

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignupPost');

        // Create the post!
        $response = $this->withAdminAccessToken()->postJson('api/v3/posts', [
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => 'voter-reg',
            'action' => 'test-action',
            'action_id' => $action->id,
            'status' => 'register-form',
            'details' => json_encode($details),
        ]);

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => 'voter-reg',
            'action' => 'test-action',
            'action_id' => $action->id,
            'status' => 'register-form',
            'details' => json_encode($details),
        ]);
    }
}
