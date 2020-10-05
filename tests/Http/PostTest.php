<?php

namespace Tests\Http;

use DoSomething\Gateway\Blink;
use Illuminate\Http\UploadedFile;
use Rogue\Models\Action;
use Rogue\Models\Campaign;
use Rogue\Models\Club;
use Rogue\Models\Group;
use Rogue\Models\Post;
use Rogue\Models\Reaction;
use Rogue\Models\Signup;
use Rogue\Models\User;
use Rogue\Services\CustomerIo;
use Rogue\Services\Fastly;
use Rogue\Services\GraphQL;
use Tests\TestCase;

class PostTest extends TestCase
{
    /**
     * Helper function to assert post structure.
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
                'media' => ['url', 'original_image_url', 'text'],
                'quantity',
                'tags' => [],
                'reactions' => ['reacted', 'total'],
                'status',
                'details',
                'location',
                'school_id',
                'club_id',
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
        $location = 'US-' . $this->faker->stateAbbr();
        $school_id = $this->faker->word;
        $details = ['source-detail' => 'broadcast-123', 'other' => 'other'];
        $referrerUserId = $this->faker->northstar_id;
        $groupId = factory(Group::class)->create()->id;

        // Create an action to refer to.
        $action = factory(Action::class)->create([
            'campaign_id' => $campaignId,
        ]);

        // Mock the Blink API calls.
        $this->mock(Blink::class)->shouldReceive('userSignup');

        // Mock the GraphQL API calls.
        $this->mock(GraphQL::class)->shouldReceive(
            'getUserById',
            'getCampaignWebsiteByCampaignId',
            'getSchoolById',
        );

        // Mock the Customer.io API calls.
        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        // Create the post!
        $response = $this->withAccessToken($northstarId)->json(
            'POST',
            'api/v3/posts',
            [
                'campaign_id' => $campaignId,
                'type' => $action->post_type,
                'action' => $action->name,
                'action_id' => $action->id,
                'quantity' => $quantity,
                'why_participated' => $why_participated,
                'text' => $text,
                'location' => $location,
                'school_id' => $school_id,
                'file' => UploadedFile::fake()->image('photo.jpg', 450, 450),
                'details' => json_encode($details),
                'referrer_user_id' => $referrerUserId,
                'group_id' => $groupId,
            ],
        );

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        // Make sure the signup & post are persisted to the database.
        $this->assertDatabaseHas('signups', [
            'campaign_id' => $campaignId,
            'northstar_id' => $northstarId,
            'why_participated' => $why_participated,
            'referrer_user_id' => $referrerUserId,
            'group_id' => $groupId,
        ]);

        $this->assertDatabaseHas('posts', [
            'northstar_id' => $northstarId,
            'campaign_id' => $campaignId,
            'type' => $action->post_type,
            'action' => $action->name,
            'action_id' => $action->id,
            'status' => 'pending',
            'location' => $location,
            'school_id' => $school_id,
            'quantity' => $quantity,
            'details' => json_encode($details),
            'referrer_user_id' => $referrerUserId,
            'group_id' => $groupId,
        ]);
    }

    /**
     * Test that a POST request to /posts creates a new
     * post and signup (if it doesn't already exist) without campaign_id.
     *
     * @return void
     */
    public function testCreatingAPostAndSignupWithoutCampaignId()
    {
        $northstarId = $this->faker->northstar_id;
        $campaignId = factory(Campaign::class)->create()->id;
        $quantity = $this->faker->numberBetween(10, 1000);
        $why_participated = $this->faker->paragraph;
        $text = $this->faker->sentence;
        $location = 'US-' . $this->faker->stateAbbr();
        $details = ['source-detail' => 'broadcast-123', 'other' => 'other'];

        // Create an action to refer to.
        $action = factory(Action::class)->create([
            'campaign_id' => $campaignId,
        ]);

        // Mock the Blink API calls.
        $this->mock(Blink::class)->shouldReceive('userSignup');
        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        // Create the post!
        $response = $this->withAccessToken($northstarId)->json(
            'POST',
            'api/v3/posts',
            [
                'type' => $action->post_type,
                'action' => $action->name,
                'action_id' => $action->id,
                'quantity' => $quantity,
                'why_participated' => $why_participated,
                'text' => $text,
                'location' => $location,
                'file' => UploadedFile::fake()->image('photo.jpg', 450, 450),
                'details' => json_encode($details),
            ],
        );

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
            'type' => $action->post_type,
            'action' => $action->name,
            'action_id' => $action->id,
            'status' => 'pending',
            'location' => $location,
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
        $action = factory(Action::class)->create([
            'campaign_id' => $signup->campaign_id,
        ]);

        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        // Create the post!
        $response = $this->withAccessToken($signup->northstar_id)->postJson(
            'api/v3/posts',
            [
                'northstar_id' => $signup->northstar_id,
                'campaign_id' => $signup->campaign_id,
                'type' => $action->post_type,
                'action' => $action->name,
                'action_id' => $action->id,
                'quantity' => $quantity,
                'why_participated' => $why_participated,
                'text' => $text,
                'file' => UploadedFile::fake()->image('photo.jpg', 450, 450),
                'details' => json_encode($details),
            ],
        );

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => $action->post_type,
            'action' => $action->name,
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
        $action = factory(Action::class)->create([
            'campaign_id' => $signup->campaign_id,
            'post_type' => 'text',
        ]);

        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        // Create the post!
        $response = $this->withAccessToken($signup->northstar_id)->postJson(
            'api/v3/posts',
            [
                'northstar_id' => $signup->northstar_id,
                'campaign_id' => $signup->campaign_id,
                'type' => $action->post_type,
                'action' => $action->name,
                'action_id' => $action->id,
                'quantity' => $quantity,
                'why_participated' => $why_participated,
                'text' => $text,
                'details' => json_encode($details),
            ],
        );

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => $action->post_type,
            'action' => $action->name,
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
     * test validation for updating a post.
     *
     * patch /api/v3/posts/195
     * @return void
     */
    public function testCreatingAPostWithValidationErrors()
    {
        $signup = factory(Signup::class)->create();

        $response = $this->withAccessToken($signup->northstar_id)->postJson(
            'api/v3/posts',
            [
                'campaign_id' => 'dog', // This should be a numeric ID.
                'signup_id' => $signup->id, // This one is okay.
                'school_id' => 234, // This should be a string.
                // and we've omitted the required 'type' and 'action' fields!
            ],
        );

        $response->assertJsonValidationErrors([
            'campaign_id',
            'type',
            'action',
            'school_id',
        ]);
    }

    /**
     * We should silently discard bad location data.
     *
     * patch /api/v3/posts/195
     * @return void
     */
    public function testHandleBorkedLocationData()
    {
        $signup = factory(Signup::class)->create();
        $action = factory(Action::class)->create([
            'campaign_id' => $signup->campaign_id,
            'post_type' => 'text',
        ]);

        $response = $this->withAccessToken($signup->northstar_id)->postJson(
            'api/v3/posts',
            [
                'action_id' => $action->id,
                'type' => 'text',
                'text' => 'Lorem ipsum dolor sit amet.',
                'location' => 'Can\'t pin me down with your rules!!',
            ],
        );

        // We should save the post, but discard the bad location:
        $response->assertSuccessful();
        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'type' => 'text',
            'text' => 'Lorem ipsum dolor sit amet.',
            'location' => null,
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
        $action = factory(Action::class)->create([
            'campaign_id' => $signup->campaign_id,
            'post_type' => 'share-social',
        ]);

        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        // Create the post!
        $response = $this->withAccessToken($signup->northstar_id)->postJson(
            'api/v3/posts',
            [
                'northstar_id' => $signup->northstar_id,
                'campaign_id' => $signup->campaign_id,
                'type' => $action->post_type,
                'action' => $action->name,
                'action_id' => $action->id,
                'quantity' => $quantity,
                'text' => $text,
                'details' => json_encode($details),
            ],
        );

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => $action->post_type,
            'action' => $action->name,
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
        $action = factory(Action::class)->create([
            'campaign_id' => $signup->campaign_id,
            'post_type' => 'share-social',
        ]);

        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        // Create the post!
        $response = $this->withAdminAccessToken()->postJson('api/v3/posts', [
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => 'share-social',
            'action' => $action->name,
            'action_id' => $action->id,
            'quantity' => $quantity,
            'text' => $text,
            'details' => json_encode($details),
        ]);

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => $action->post_type,
            'action' => $action->name,
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
        $action = factory(Action::class)->create([
            'campaign_id' => $signup->campaign_id,
            'post_type' => 'share-social',
        ]);

        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        // Create the post!
        $response = $this->withAdminAccessToken()->postJson('api/v3/posts', [
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => $action->post_type,
            'action' => $action->name,
            'action_id' => $action->id,
            'quantity' => $quantity,
            'text' => $text,
            'details' => json_encode($details),
            'status' => 'pending',
        ]);

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => $action->post_type,
            'action' => $action->name,
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

        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        // Create the post with an invalid type (not in text, photo, voter-reg, share-social).
        $response = $this->withAccessToken($signup->northstar_id)->postJson(
            'api/v3/posts',
            [
                'northstar_id' => $signup->northstar_id,
                'campaign_id' => $signup->campaign_id,
                'type' => 'social-share',
                'action' => 'test-action',
                'quantity' => $quantity,
                'text' => $text,
                'details' => json_encode($details),
            ],
        );

        $response->assertStatus(422);
        $this->assertEquals(
            'The selected type is invalid.',
            $response->decodeResponseJson()['errors']['type'][0],
        );
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

        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        // Make sure you also need the activity scope.
        $response = $this->postJson('api/v3/posts', [
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => 'photo',
            'action' => 'test-action',
            'quantity' => $quantity,
            'why_participated' => $this->faker->paragraph,
            'text' => $text,
            'file' => UploadedFile::fake()->image('photo.jpg', 450, 450),
        ]);

        $response->assertStatus(401);
        $this->assertEquals(
            'Unauthenticated.',
            $response->decodeResponseJson()['message'],
        );
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
        $action = factory(Action::class)->create([
            'campaign_id' => $signup->campaign_id,
        ]);

        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        // Create the post!
        $response = $this->withAccessToken($signup->northstar_id)->postJson(
            'api/v3/posts',
            [
                'northstar_id' => $signup->northstar_id,
                'campaign_id' => $signup->campaign_id,
                'type' => $action->post_type,
                'action' => $action->name,
                'action_id' => $action->id,
                'quantity' => $quantity,
                'why_participated' => $this->faker->paragraph,
                'text' => $text,
                'file' => UploadedFile::fake()->image('photo.jpg', 450, 450),
                'details' => json_encode($details),
            ],
        );

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => $action->post_type,
            'action' => $action->name,
            'action_id' => $action->id,
            'status' => 'pending',
            'quantity' => $quantity,
            'details' => json_encode($details),
        ]);

        // Create a second post without why_participated.
        $secondQuantity = $this->faker->numberBetween(10, 1000);
        $secondText = $this->faker->sentence;
        $secondDetails = [
            'source-detail' => 'broadcast-333',
            'other' => 'other',
        ];

        $response = $this->withAccessToken($signup->northstar_id)->postJson(
            'api/v3/posts',
            [
                'northstar_id' => $signup->northstar_id,
                'campaign_id' => $signup->campaign_id,
                'type' => $action->post_type,
                'action' => $action->name,
                'action_id' => $action->id,
                'quantity' => $secondQuantity,
                'text' => $secondText,
                'file' => UploadedFile::fake()->image('photo.jpg', 450, 450),
                'details' => json_encode($secondDetails),
            ],
        );

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => $action->post_type,
            'action' => $action->name,
            'action_id' => $action->id,
            'status' => 'pending',
            'quantity' => $secondQuantity,
            'details' => json_encode($secondDetails),
        ]);

        // Assert that signup quantity is sum of all posts' quantities.
        $this->assertEquals(
            $signup->fresh()->quantity,
            $quantity + $secondQuantity,
        );
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
        $action = factory(Action::class)->create([
            'campaign_id' => $signup->campaign_id,
        ]);

        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        // Create the post!
        $response = $this->withAccessToken(
            $signup->northstar_id,
            'user',
        )->postJson('api/v3/posts', [
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => $action->post_type,
            'action' => $action->name,
            'action_id' => $action->id,
            'quantity' => null,
            'text' => $text,
            'file' => UploadedFile::fake()->image('photo.jpg', 450, 450),
            'details' => json_encode($details),
        ]);

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => $action->post_type,
            'action' => $action->name,
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
        $action = factory(Action::class)->create([
            'campaign_id' => $signup->campaign_id,
        ]);

        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        // Create the post!
        $response = $this->withAccessToken($signup->northstar_id)->postJson(
            'api/v3/posts',
            [
                'northstar_id' => $signup->northstar_id,
                'campaign_id' => $signup->campaign_id,
                'type' => $action->post_type,
                'action' => $action->name,
                'action_id' => $action->id,
                'text' => $text,
                'file' => UploadedFile::fake()->image('photo.jpg', 450, 450),
            ],
        );

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => $action->post_type,
            'action' => $action->name,
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

        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        // Create the post!
        $response = $this->postJson('api/v3/posts', [
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => 'photo',
            'action' => 'test-action',
            'quantity' => $quantity,
            'why_participated' => $this->faker->paragraph,
            'text' => $text,
            'file' => UploadedFile::fake()->image('photo.jpg', 450, 450),
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test creating a post without sending an action_id.
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
        ]);

        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        // Create the post without sending an action_id!
        $response = $this->withAccessToken($signup->northstar_id)->postJson(
            'api/v3/posts',
            [
                'northstar_id' => $signup->northstar_id,
                'campaign_id' => $signup->campaign_id,
                'type' => $action->post_type,
                'action' => 'test-action',
                'quantity' => $quantity,
                'why_participated' => $this->faker->paragraph,
                'text' => $text,
                'file' => UploadedFile::fake()->image('photo.jpg', 450, 450),
            ],
        );

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => $action->post_type,
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
        factory(Post::class, 10)
            ->states('photo', 'accepted')
            ->create();
        factory(Post::class, 5)
            ->states('photo', 'rejected')
            ->create();

        $response = $this->getJson('api/v3/posts');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'signup_id',
                    'northstar_id',
                    'media' => ['url', 'original_image_url', 'text'],
                    'quantity',
                    'reactions' => ['reacted', 'total'],
                    'status',
                    'created_at',
                    'updated_at',
                ],
            ],
            'meta' => [
                'cursor' => ['current', 'prev', 'next', 'count'],
            ],
        ]);
    }

    /**
     * Test for retrieving all posts with some anonymous posts.
     * Non-owners should not northstar_id or location.
     *
     * GET /api/v3/posts
     * @return void
     */
    public function testPostsIndexWithAnonymousPosts()
    {
        // Create an accepted post.
        $this->mockTime('8/3/2017 14:00:00');
        $regularPost = factory(Post::class)
            ->states('photo', 'accepted')
            ->create([
                'northstar_id' => $this->faker->unique()->northstar_id,
            ]);

        // And then later, create an anonymous post from another user.
        $this->mockTime('8/3/2017 17:30:00');
        $anonymousPost = factory(Post::class)
            ->states('photo', 'accepted')
            ->create([
                'northstar_id' => $this->faker->unique()->northstar_id,
            ]);
        $anonymousPost->actionModel->anonymous = 1;
        $anonymousPost->actionModel->save();

        // Anonymously hit the endpoint and northstar_id should not be returned for the anonymous post (first post since it was most recently created).
        $response = $this->getJson('api/v3/posts');
        $response->assertStatus(200);

        $this->assertArrayNotHasKey(
            'northstar_id',
            $response->decodeResponseJson()['data'][0],
        );
        $this->assertEquals(
            $regularPost->northstar_id,
            $response->decodeResponseJson()['data'][1]['northstar_id'],
        );

        // Hit the endpoint with access credentials from another user and should have same results as above.
        $response = $this->withAccessToken($regularPost->northstar_id)->getJson(
            'api/v3/posts',
        );
        $response->assertStatus(200);
        $this->assertArrayNotHasKey(
            'northstar_id',
            $response->decodeResponseJson()['data'][0],
        );
        $this->assertEquals(
            $regularPost->northstar_id,
            $response->decodeResponseJson()['data'][1]['northstar_id'],
        );

        // Hit the endpoint with filter[northstar_id] and no posts should be returned.
        $response = $this->getJson(
            'api/v3/posts?filter[northstar_id]=' . $anonymousPost->northstar_id,
        );
        $response->assertStatus(200);
        $this->assertEquals(
            0,
            $response->decodeResponseJson()['meta']['cursor']['count'],
        );

        $response = $this->withAccessToken($regularPost->northstar_id)->getJson(
            'api/v3/posts?filter[northstar_id]=' . $anonymousPost->northstar_id,
        );
        $response->assertStatus(200);
        $this->assertEquals(
            0,
            $response->decodeResponseJson()['meta']['cursor']['count'],
        );

        // Hit the endpoint as the owner of the post and you should be able to see northstar_id for anonymous post.
        $response = $this->withAccessToken(
            $anonymousPost->northstar_id,
        )->getJson('api/v3/posts');
        $response->assertStatus(200);
        $this->assertEquals(
            $anonymousPost->northstar_id,
            $response->decodeResponseJson()['data'][0]['northstar_id'],
        );
        $this->assertEquals(
            $regularPost->northstar_id,
            $response->decodeResponseJson()['data'][1]['northstar_id'],
        );

        // Hit the endpoint with filter[northstar_id] and should have same results as above.
        $response = $this->withAccessToken(
            $anonymousPost->northstar_id,
        )->getJson(
            'api/v3/posts?filter[northstar_id]=' . $anonymousPost->northstar_id,
        );
        $response->assertStatus(200);
        $this->assertEquals(
            $anonymousPost->northstar_id,
            $response->decodeResponseJson()['data'][0]['northstar_id'],
        );

        // Hit the endpoint with admin credentials and you should be able to see northstar_id for anonymous post.
        $response = $this->withAdminAccessToken()->getJson('api/v3/posts');
        $response->assertStatus(200);
        $this->assertEquals(
            $anonymousPost->northstar_id,
            $response->decodeResponseJson()['data'][0]['northstar_id'],
        );
        $this->assertEquals(
            $regularPost->northstar_id,
            $response->decodeResponseJson()['data'][1]['northstar_id'],
        );

        // Hit the endpoint with filter[northstar_id] and should have same results as above.
        $response = $this->withAdminAccessToken()->getJson(
            'api/v3/posts?filter[northstar_id]=' . $anonymousPost->northstar_id,
        );
        $response->assertStatus(200);
        $this->assertEquals(
            $anonymousPost->northstar_id,
            $response->decodeResponseJson()['data'][0]['northstar_id'],
        );
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
        factory(Post::class, 10)
            ->states('photo', 'accepted')
            ->create();

        $hiddenPost = factory(Post::class)
            ->states('photo', 'accepted')
            ->create();
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
        factory(Post::class, 10)
            ->states('photo', 'accepted')
            ->create();
        factory(Post::class, 5)
            ->states('photo', 'rejected')
            ->create();

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
                    'media' => ['url', 'original_image_url', 'text'],
                    'quantity',
                    'reactions' => ['reacted', 'total'],
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
                'cursor' => ['current', 'prev', 'next', 'count'],
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
        factory(Post::class, 10)
            ->states('photo', 'accepted')
            ->create();

        $hiddenPost = factory(Post::class)
            ->states('photo', 'accepted')
            ->create();
        $hiddenPost->tag('Hide In Gallery');

        $response = $this->withAdminAccessToken()->getJson('api/v3/posts');
        $response->assertStatus(200);
        $response->assertJsonCount(11, 'data');
    }

    /**
     * Test for retrieving posts as admin filtered by status.
     *
     * GET /api/v3/posts
     * @return void
     */
    public function testPostsIndexFilteredByStatusAsAdmin()
    {
        factory(Post::class, 1)
            ->states('photo', 'accepted')
            ->create();
        factory(Post::class, 5)
            ->states('photo', 'pending')
            ->create();
        factory(Post::class, 7)
            ->states('photo', 'rejected')
            ->create();

        // Admins should see posts filtered by pending status.
        $response = $this->withAdminAccessToken()->getJson(
            'api/v3/posts?filter[status]=pending',
        );

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');

        // Admins should be able to filter by multiple statuses and see pending and rejected posts.
        $response = $this->withAdminAccessToken()->getJson(
            'api/v3/posts?filter[status]=pending,rejected',
        );

        $response->assertStatus(200);
        $response->assertJsonCount(12, 'data');
    }

    /**
     * Test for retrieving posts filtered by the volunteer_credit value of the associated action.
     *
     * GET /api/v3/posts
     * @return void
     */
    public function testPostsIndexFilteredByVolunteerCredit()
    {
        $action = factory(Action::class)->create([
            'volunteer_credit' => true,
        ]);
        // Posts qualifying for volunteer credit:
        factory(Post::class, 4)
            ->states('photo', 'accepted')
            ->create([
                'action_id' => $action->id,
            ]);
        // Posts not qualifying for volunteer credit:
        factory(Post::class, 7)
            ->states('photo', 'accepted')
            ->create();

        $response = $this->getJson(
            'api/v3/posts?filter[volunteer_credit]=true',
        );
        $response->assertSuccessful();
        $response->assertJsonCount(4, 'data');

        $response = $this->getJson(
            'api/v3/posts?filter[volunteer_credit]=false',
        );
        $response->assertSuccessful();
        $response->assertJsonCount(7, 'data');
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
        factory(Post::class, 2)
            ->states('photo', 'pending')
            ->create(['northstar_id' => $userId]);
        factory(Post::class)
            ->states('photo', 'rejected')
            ->create(['northstar_id' => $userId]);

        $otherId = $this->faker->unique()->northstar_id;
        factory(Post::class, 4)
            ->states('photo', 'rejected')
            ->create(['northstar_id' => $otherId]);

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
                    'media' => ['url', 'original_image_url', 'text'],
                    'quantity',
                    'reactions' => ['reacted', 'total'],
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
                'cursor' => ['current', 'prev', 'next', 'count'],
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
        $ownerId = $this->faker->unique()->northstar_id;

        // Create posts and associate to this $ownerId.
        $posts = factory(Post::class, 2)
            ->states('photo', 'accepted')
            ->create(['northstar_id' => $ownerId]);

        // Create a hidden post from the same $ownerId.
        $hiddenPost = factory(Post::class)
            ->states('photo', 'accepted')
            ->create(['northstar_id' => $ownerId]);
        $hiddenPost->tag('Hide In Gallery');
        $hiddenPost->save();

        // Create anothter hidden post by different user.
        $secondHiddenPost = factory(Post::class)
            ->states('photo', 'accepted')
            ->create([
                'northstar_id' => $this->faker->unique()->northstar_id,
            ]);
        $secondHiddenPost->tag('Hide In Gallery');
        $secondHiddenPost->save();

        $response = $this->withAccessToken($ownerId)->getJson('api/v3/posts');
        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    /**
     * Test for retrieving voter registration posts as a referrer.
     * A referrer should see any voter registration post that they have referred.
     *
     * GET /api/v3/posts
     * @return void
     */
    public function testPostsIndexAsVoterRegistrationReferrer()
    {
        $referrerUserId = $this->faker->unique()->northstar_id;

        // A referrer can see all of their voter registration referrals.
        $firstVoterRegReferral = factory(Post::class)
            ->states('voter-reg', 'register-form')
            ->create(['referrer_user_id' => $referrerUserId]);
        $secondVoterRegReferral = factory(Post::class)
            ->states('voter-reg', 'rejected')
            ->create(['referrer_user_id' => $referrerUserId]);
        $thirdVoterRegReferral = factory(Post::class)
            ->states('voter-reg', 'step-1')
            ->create(['referrer_user_id' => $referrerUserId]);

        // Add a completed voter reg without a referrer, which is public because it's completed.
        $publicVoterRegPost = factory(Post::class)
            ->states('voter-reg', 'register-OVR')
            ->create();

        // Add non-completed voter referrals for a different referrer, which shouldn't be visible.
        factory(Post::class)
            ->states('voter-reg', 'step-1')
            ->create([
                'referrer_user_id' => $this->faker->unique()->northstar_id,
            ]);

        // Add a pending photo post, which shouldn't be visible.
        factory(Post::class)
            ->states('photo', 'pending')
            ->create();

        $response = $this->withAccessToken($referrerUserId)->getJson(
            'api/v3/posts',
        );
        $response->assertStatus(200);
        $response->assertJsonCount(4, 'data');
        $response->assertJsonFragment([
            'id' => $firstVoterRegReferral->id,
            'status' => 'register-form',
        ]);
        $response->assertJsonFragment([
            'id' => $secondVoterRegReferral->id,
            'status' => 'rejected',
        ]);
        $response->assertJsonFragment([
            'id' => $thirdVoterRegReferral->id,
            'status' => 'step-1',
        ]);
        $response->assertJsonFragment([
            'id' => $publicVoterRegPost->id,
            'status' => 'register-OVR',
        ]);
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
        $post = factory(Post::class)
            ->states('photo', 'rejected')
            ->create();

        $response = $this->getJson('api/v3/posts/' . $post->id);

        $response->assertStatus(403);

        // Anon user is able to see an accepted post even if it doesn't belong to them and if they're not an admin.
        $post = factory(Post::class)
            ->states('photo', 'accepted')
            ->create();

        $response = $this->getJson('api/v3/posts/' . $post->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'type',
                'action',
                'media' => ['url', 'original_image_url', 'text'],
                'quantity',
                'reactions' => ['reacted', 'total'],
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

        $response = $this->withAdminAccessToken()->getJson(
            'api/v3/posts/' . $post->id,
        );

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
        $response = $this->withAccessToken($post->northstar_id)->getJson(
            'api/v3/posts/' . $post->id,
        );

        $response->assertStatus(200);
        // $this->assertPostStructure($response);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'signup_id',
                'northstar_id',
                'type',
                'action',
                'media' => ['url', 'original_image_url', 'text'],
                'quantity',
                'reactions' => ['reacted', 'total'],
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
        $post = factory(Post::class)
            ->states('photo', 'accepted')
            ->create();

        // Create two reactions for this post!
        Reaction::withTrashed()->firstOrCreate([
            'northstar_id' => $viewer,
            'post_id' => $post->id,
        ]);
        Reaction::withTrashed()->firstOrCreate([
            'northstar_id' => 'someone_else_lol',
            'post_id' => $post->id,
        ]);

        $response = $this->withAccessToken($viewer, 'user')->getJson(
            'api/v3/posts/' . $post->id,
        );

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
     * PATCH /api/v3/posts/:id
     * @return void
     */
    public function testUpdatingAPhotoPost()
    {
        $post = factory(Post::class)->create();
        $signup = $post->signup;

        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        $response = $this->withAdminAccessToken()->patchJson(
            'api/v3/posts/' . $post->id,
            [
                'text' => 'new caption',
                'quantity' => 8,
                'status' => 'accepted',
                'school_id' => '200426',
            ],
        );

        $response->assertStatus(200);

        // Make sure that the post's new status, text, and school_id gets persisted in the database.
        $this->assertEquals($post->fresh()->text, 'new caption');
        $this->assertEquals($post->fresh()->quantity, 8);
        $this->assertEquals($post->fresh()->school_id, '200426');

        // Make sure the signup's quantity gets updated.
        $this->assertEquals($signup->fresh()->quantity, 8);
    }

    /**
     * Test that updating a referral post sends an event to Customer.io.
     *
     * PATCH /api/v3/posts/:id
     * @return void
     */
    public function testUpdatingAReferralPost()
    {
        $post = factory(Post::class)->create([
            'referrer_user_id' => $this->faker->northstar_id,
            'type' => 'voter-reg',
            'status' => 'step-1',
        ]);

        $this->mock(CustomerIo::class)
            ->shouldReceive('trackEvent')
            ->with(
                $post->referrer_user_id,
                'referral_post_updated',
                $post->getReferralPostEventPayload(),
            );

        $response = $this->withAdminAccessToken()->patchJson(
            'api/v3/posts/' . $post->id,
            [
                'status' => 'register-form',
            ],
        );
    }

    /**
     * Test for updating a post with invalid status.
     *
     * PATCH /api/v3/posts/:id
     * @return void
     */
    public function testUpdatingAPhotoWithInvalidStatus()
    {
        $post = factory(Post::class)->create();

        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        $response = $this->withAdminAccessToken()->patchJson(
            'api/v3/posts/' . $post->id,
            [
                'text' => 'new caption',
                'quantity' => 8,
                'status' => 'register-form',
            ],
        );

        $response->assertJsonValidationErrors(['status']);
    }

    /**
     * Test for updating a post with invalid school_id.
     *
     * PATCH /api/v3/posts/:id
     * @return void
     */
    public function testUpdatingAPhotoWithInvalidSchoolId()
    {
        $post = factory(Post::class)->create();

        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        $response = $this->withAdminAccessToken()->patchJson(
            'api/v3/posts/' . $post->id,
            [
                'school_id' => 8,
            ],
        );

        $response->assertJsonValidationErrors(['school_id']);
    }

    /**
     * Test for updating a post without activity scope.
     *
     * PATCH /api/v3/posts/:id
     * @return void
     */
    public function testUpdatingAPostWithoutActivityScope()
    {
        $post = factory(Post::class)->create();
        $signup = $post->signup;

        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        $response = $this->patchJson('api/v3/posts/' . $post->id, [
            'text' => 'new caption',
            'quantity' => 8,
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test validation for updating a post.
     *
     * PATCH /api/v3/posts/:id
     * @return void
     */
    public function testValidationUpdatingAPost()
    {
        $post = factory(Post::class)->create();

        $response = $this->withAdminAccessToken()->patchJson(
            'api/v3/posts/' . $post->id,
            [
                'quantity' => 'this is words not a number!',
                'text' => 'a' . str_repeat('h', 512), // ahhh...hhhhh!
            ],
        );

        $response->assertJsonValidationErrors(['quantity', 'text']);
    }

    /**
     * Test that a user can update their own post, but can't
     * change its review status.
     *
     * @return void
     */
    public function testNonStaffUpdatePost()
    {
        $post = factory(Post::class)->create();

        $response = $this->withAccessToken($post->northstar_id)->patchJson(
            'api/v3/posts/' . $post->id,
            [
                'status' => 'accepted',
                'location' => 'US-MA',
                'text' => 'new caption',
            ],
        );

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'media' => [
                    'text' => 'new caption', // check!
                ],
                'location' => 'US-MA', // check!
                'status' => 'pending', // no way, buddy!
            ],
        ]);
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

        $response = $this->withAccessToken($user->id)->patchJson(
            'api/v3/posts/' . $post->id,
            [
                'status' => 'accepted',
                'text' => 'new caption',
            ],
        );

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
        $this->mock(Fastly::class)->shouldReceive('purge');

        $response = $this->withAdminAccessToken()->deleteJson(
            'api/v3/posts/' . $post->id,
        );

        $response->assertStatus(200);

        // Make sure that the post's deleted_at gets persisted in the database.
        $this->assertEquals(
            $post->fresh()->deleted_at->toTimeString(),
            '14:00:00',
        );
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
        $this->assertEquals(
            'Unauthenticated.',
            $response->decodeResponseJson()['message'],
        );
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
     * Test creating voter-reg post.
     *
     * @return void
     */
    public function testCreatingVoterRegistrationPost()
    {
        $signup = factory(Signup::class)->create();
        $action = factory(Action::class)->create([
            'campaign_id' => $signup->campaign_id,
            'post_type' => 'voter-reg',
        ]);

        $details = [
            'hostname' => 'dosomething.turbovote.org',
            'referral-code' =>
                'user:5570af2c469c6430068bc501,campaign:8022,source:web',
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
        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        // Create the post!
        $response = $this->withAdminAccessToken()->postJson('api/v3/posts', [
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => $action->post_type,
            'action' => $action->name,
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
            'type' => $action->post_type,
            'action' => $action->name,
            'action_id' => $action->id,
            'status' => 'register-form',
            'details' => json_encode($details),
        ]);
    }

    /**
     * Test that when a post is created for a signup with a group_id
     * the group_id is saved to the post as well.
     *
     * @return void
     */
    public function testCreatingAPostForSignupWithGroupId()
    {
        $groupId = factory(Group::class)->create()->id;
        $signup = factory(Signup::class)->create(['group_id' => $groupId]);

        // Attributes for the post that we'll create
        $quantity = $this->faker->numberBetween(10, 1000);
        $text = $this->faker->sentence;
        $action = factory(Action::class)->create([
            'campaign_id' => $signup->campaign_id,
        ]);

        // Mock the Blink API call.
        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        // Create the post!
        $response = $this->withAccessToken($signup->northstar_id)->postJson(
            'api/v3/posts',
            [
                'northstar_id' => $signup->northstar_id,
                'campaign_id' => $signup->campaign_id,
                'type' => $action->post_type,
                'action_id' => $action->id,
                'quantity' => $quantity,
                'text' => $text,
                'file' => UploadedFile::fake()->image('photo.jpg', 450, 450),
            ],
        );

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'group_id' => $signup->group_id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => $action->post_type,
            'action' => $action->name,
            'action_id' => $action->id,
            'status' => 'pending',
            'quantity' => $quantity,
        ]);
    }

    /**
     * Test that when a post is created for a signup with a club_id
     * the club_id is saved to the post as well.
     *
     * @return void
     */
    public function testCreatingAPostForSignupWithClubId()
    {
        // Turn on the feature flag for tracking club_ids.
        config(['features.track_club_id' => 'true']);

        $clubId = factory(Club::class)->create()->id;
        $signup = factory(Signup::class)->create(['club_id' => $clubId]);

        // Attributes for the post that we'll create
        $quantity = $this->faker->numberBetween(10, 1000);
        $text = $this->faker->sentence;
        $action = factory(Action::class)->create([
            'campaign_id' => $signup->campaign_id,
        ]);

        // Mock the Blink API call.
        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        // Create the post!
        $response = $this->withAccessToken($signup->northstar_id)->postJson(
            'api/v3/posts',
            [
                'northstar_id' => $signup->northstar_id,
                'campaign_id' => $signup->campaign_id,
                'type' => $action->post_type,
                'action_id' => $action->id,
                'quantity' => $quantity,
                'text' => $text,
                'file' => UploadedFile::fake()->image('photo.jpg', 450, 450),
            ],
        );

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'signup_id' => $signup->id,
            'club_id' => $signup->club_id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'type' => $action->post_type,
            'action' => $action->name,
            'action_id' => $action->id,
            'status' => 'pending',
            'quantity' => $quantity,
        ]);
    }

    /**
     * Test that when a post with a group_id is created and no signup exists yet
     * the group_id on the post is not overwritten.
     *
     * @return void
     */
    public function testCreatingAPostWithGroupIdAndNoExistingSignup()
    {
        $groupId = factory(Group::class)->create()->id;

        // Attributes for the post that we'll create
        $quantity = $this->faker->numberBetween(10, 1000);
        $text = $this->faker->sentence;
        $campaign_id = factory(Campaign::class)->create()->id;
        $action = factory(Action::class)->create([
            'campaign_id' => $campaign_id,
        ]);
        $northstar_id = $this->faker->northstar_id;

        // Mock the Blink API call.
        $this->mock(CustomerIo::class)->shouldReceive('trackEvent');

        // Create the post!
        $response = $this->withAccessToken($northstar_id)->postJson(
            'api/v3/posts',
            [
                'northstar_id' => $northstar_id,
                'campaign_id' => $campaign_id,
                'type' => $action->post_type,
                'action_id' => $action->id,
                'quantity' => $quantity,
                'text' => $text,
                'file' => UploadedFile::fake()->image('photo.jpg', 450, 450),
                'group_id' => $groupId,
            ],
        );

        $response->assertStatus(201);
        $this->assertPostStructure($response);

        $this->assertDatabaseHas('posts', [
            'group_id' => $groupId,
            'northstar_id' => $northstar_id,
            'campaign_id' => $campaign_id,
            'type' => $action->post_type,
            'action' => $action->name,
            'action_id' => $action->id,
            'status' => 'pending',
            'quantity' => $quantity,
        ]);
    }
}
