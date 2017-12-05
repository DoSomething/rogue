<?php

namespace Tests\Http\Three;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\Signup;
use DoSomething\Gateway\Blink;

class SignupTest extends TestCase
{
    /**
     * Test that a POST request to /signups creates a new signup.
     *
     * POST /api/v3/signups
     * @return void
     */
    public function testCreatingASignup()
    {
        $campaignId = str_random(22);
        $campaignRunId = $this->faker->randomNumber(4);

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignup');

        $response = $this->withAdminAccessToken()->postJson('api/v3/signups', [
            'campaign_id'      => $campaignId,
            'campaign_run_id'  => $campaignRunId,
            'details'          => 'affiliate-messaging',
        ]);

        // Make sure we get the 201 Created response
        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'campaign_id' => $campaignId,
                'campaign_run_id' => $campaignRunId,
                'quantity' => null,
                'source' => 'phpunit',
                'why_participated' => null,
            ],
        ]);

        // Make sure the signup is persisted.
        $this->assertDatabaseHas('signups', [
            'campaign_id' => $campaignId,
            'campaign_run_id' => $campaignRunId,
            'quantity' => null,
            'details' => 'affiliate-messaging',
        ]);
    }

    /**
     * Test that a POST request to /signups doesn't create duplicate signups.
     *
     * POST /api/v3/signups
     * @return void
     */
    public function testNotCreatingDuplicateSignups()
    {
        $signup = factory(Signup::class)->states('contentful')->create();

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignup');

        $response = $this->withAdminAccessToken()->postJson('api/v3/signups', [
            'campaign_id'      => $signup->campaign_id,
            'source'           => 'the-fox-den',
            'details'          => 'affiliate-messaging',
        ]);

        // Make sure we get the 200 response
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'campaign_id' => $signup->campaign_id,
                'campaign_run_id' => null,
                'quantity' => $signup->getQuantity(),
            ],
        ]);
    }

    /**
     * Test that non-authenticated user's/apps can't post signups.
     *
     * @return void
     */
    public function testUnauthenticatedUserCreatingASignup()
    {
        $response = $this->postJson('api/v3/signups', [
            'northstar_id'     => '54fa272b469c64d7068b456a',
            'campaign_id'      => '6LQzMvDNQcYQYwso8qSkQ8',
            'campaign_run_id'  => $this->faker->randomNumber(4),
            'source'           => 'the-fox-den',
            'details'          => 'affiliate-messaging',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test for retrieving all signups.
     *
     * GET /api/v3/signups
     * @return void
     */
    public function testSignupsIndex()
    {
        factory(Signup::class, 10)->create();

        $response = $this->getJson('api/v3/signups');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'northstar_id',
                    'campaign_id',
                    'campaign_run_id',
                    'quantity',
                    'why_participated',
                    'source',
                    'details',
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
     * Test for retrieving a specific signup.
     *
     * GET /api/v3/signups/:signup_id
     * @return void
     */
    public function testSignupShow()
    {
        $signup = factory(Signup::class)->create();
        $response = $this->getJson('api/v3/signups/' . $signup->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'northstar_id',
                'campaign_id',
                'campaign_run_id',
                'quantity',
                'why_participated',
                'source',
                'details',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    /**
     * Test for retrieving a signup with included post info.
     *
     * GET /api/v3/signups/186?include=posts
     * @return void
     */
    public function testSignupWithIncludedPosts()
    {
        $signup = factory(Signup::class)->create();
        $post = factory(Post::class)->create();
        $post->signup()->associate($signup);
        $post->save();

        $response = $this->getJson('api/v3/signups/' . $signup->id . '?include=posts');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'posts' => [
                    'data' => [
                        '*' => [
                            'id',
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Test that a signup gets deleted when hitting the DELETE endpoint.
     *
     * @return void
     */
    public function testDeletingASignup()
    {
        $signup = factory(Signup::class)->create();

        // Mock time of when the signup is soft deleted.
        $this->mockTime('8/3/2017 14:00:00');

        $response = $this->withAdminAccessToken()->deleteJson('api/v3/signups/' . $signup->id);

        $response->assertStatus(200);

        // Make sure that the signup's deleted_at gets persisted in the database.
        $this->assertEquals($signup->fresh()->deleted_at->toTimeString(), '14:00:00');
    }

    /**
     * Test that non-authenticated user's/apps can't delete signups.
     *
     * @return void
     */
    public function testUnauthenticatedUserDeletingASignup()
    {
        $signup = factory(Signup::class)->create();

        $response = $this->deleteJson('api/v3/signups/' . $signup->id);

        $response->assertStatus(401);
    }

    /**
     * Test for updating a signup successfully.
     *
     * PATCH /api/v3/signups/186
     * @return void
     */
    public function testUpdatingASignup()
    {
        $signup = factory(Signup::class)->create();

        $response = $this->withAdminAccessToken()->patchJson('api/v3/signups/' . $signup->id, [
            'why_participated'  => 'new why participated',
        ]);

        $response->assertStatus(200);

        // Make sure that the signup's new why_participated gets persisted in the database.
        $this->assertEquals($signup->fresh()->why_participated, 'new why participated');
    }

    /**
     * Test valudation for updating a signup.
     *
     * PATCH /api/v3/signups/186
     * @return void
     */
    public function testValidationForUpdatingASignup()
    {
        $signup = factory(Signup::class)->create();

        $response = $this->withAdminAccessToken()->patchJson('api/v3/signups/' . $signup->id);

        $response->assertStatus(422);
    }

    /**
     * Test that non-authenticated user's/apps can't update signups.
     *
     * @return void
     */
    public function testUnauthenticatedUserUpdatingASignup()
    {
        $signup = factory(Signup::class)->create();

        $response = $this->patchJson('api/v3/signups/' . $signup->id, [
            'why_participated'  => 'new why participated',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test to make sure we are returning quantity correctly.
     * Quantity is either summed total of quantity across all posts under a signup
     * or quanttiy under signup if it is still on signup record.
     *
     * GET /signups
     * @return void
     */
    public function testQuantityOnSignupIndex()
    {
        // Create a signup with a quantity.
        $firstSignup = factory(Signup::class)->create();
        $firstSignup->quantity = 8;
        $firstSignup->save();

        // Create another signup with three posts with quantities.
        $secondSignup = factory(Signup::class)->create();

        $posts = factory(Post::class, 3)->create(['signup_id' => $secondSignup->id]);

        // Update the signup quantity to equal the sum of post quantities.
        $secondSignup->quantity = $secondSignup->getQuantity();
        $secondSignup->save();

        $response = $this->getJson('api/v3/signups');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'quantity' => 8,
                    // ...
                ],
                [
                    'quantity' => $secondSignup->quantity,
                    // ...
                ],
            ],
        ]);
    }
}
