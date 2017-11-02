<?php

namespace Tests\Http\Three;

use Rogue\Models\Post;
use Rogue\Models\Signup;
use Tests\BrowserKitTestCase;
use DoSomething\Gateway\Blink;

class SignupTest extends BrowserKitTestCase
{
    /**
     * Test that a POST request to /signups creates a new signup.
     *
     * POST /api/v3/signups
     * @return void
     */
    public function testCreatingASignup()
    {
        $northstarId = $this->faker->uuid;
        $campaignId =  str_random(22);
        $campaignRunId = $this->faker->randomNumber(4);

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignup');

        $this->withRogueApiKey()->json('POST', 'api/v3/signups', [
            'northstar_id'     => $northstarId,
            'campaign_id'      => $campaignId,
            'campaign_run_id'  => $campaignRunId,
            'source'           => 'the-fox-den',
            'details'          => 'affiliate-messaging',
        ]);

        // Make sure we get the 201 Created response
        $this->assertResponseStatus(201);
        $this->seeJson([
            'northstar_id' => $northstarId,
            'campaign_id' => $campaignId,
            'campaign_run_id' => $campaignRunId,
            'source' => 'the-fox-den',
            'quantity' => null,
            'why_participated' => null,
        ]);

        // Make sure the signup is persisted.
        $this->seeInDatabase('signups', [
            'northstar_id' => $northstarId,
            'campaign_id' => $campaignId,
            'campaign_run_id' => $campaignRunId,
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
        // $signup = factory(Signup::class)->create();
        $signup = factory(Signup::class)->states('contentful')->create();
        // Mock the Blink API call.
        // dd($signup->campaign_id);
        $this->mock(Blink::class)->shouldReceive('userSignup');

        $this->withRogueApiKey()->json('POST', 'api/v3/signups', [
            'northstar_id'     => $signup->northstar_id,
            'campaign_id'      => $signup->campaign_id,
            'source'           => 'the-fox-den',
            'details'          => 'affiliate-messaging',
        ]);

        // Make sure we get the 200 response
        $this->assertResponseStatus(200);
        $this->seeJson([
            'campaign_id' => $signup->campaign_id,
            'campaign_run_id' => null,
        ]);
    }

    /**
     * Test that non-authenticated user's/apps can't post signups.
     *
     * @return void
     */
    public function testUnauthenticatedUserCreatingASignup()
    {
        $northstarId = '54fa272b469c64d7068b456a';
        $campaignId = '6LQzMvDNQcYQYwso8qSkQ8';
        $campaignRunId = $this->faker->randomNumber(4);

        $response = $this->json('POST', 'api/v3/signups', [
            'northstar_id'     => $northstarId,
            'campaign_id'      => $campaignId,
            'campaign_run_id'  => $campaignRunId,
            'source'           => 'the-fox-den',
            'details'          => 'affiliate-messaging',
        ]);

        $response->assertResponseStatus(401);
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

        $this->get('api/v3/signups');

        $this->assertResponseStatus(200);
        $this->seeJsonStructure([
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
        $this->get('api/v3/signups/' . $signup->id);

        $this->assertResponseStatus(200);
        $this->seeJsonStructure([
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

        $this->get('api/v3/signups/' . $signup->id . '?include=posts');
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
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

        $response = $this->withRogueApiKey()->delete('api/v3/signups/' . $signup->id);

        $this->assertResponseStatus(200);

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

        $response->assertResponseStatus(401);
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

        $response = $this->withRogueApiKey()->json('PATCH', 'api/v3/signups/' . $signup->id, [
            'quantity'     => 888,
            'why_participated'  => 'new why participated',
        ]);

        $this->assertResponseStatus(200);

        // Make sure that the signup's new quantity and why_participated gets persisted in the database.
        $this->assertEquals($signup->fresh()->quantity, 888);
        $this->assertEquals($signup->fresh()->why_participated, 'new why participated');
    }

    /**
     * Test valudation for updating a signup.
     *
     * PATCH /api/v3/signups/186
     * @return void
     */
    public function testValidationgForUpdatingASignup()
    {
        $signup = factory(Signup::class)->create();

        $response = $this->withRogueApiKey()->json('PATCH', 'api/v3/signups/' . $signup->id);

        $this->assertResponseStatus(422);
    }

    /**
     * Test that non-authenticated user's/apps can't update signups.
     *
     * @return void
     */
    public function testUnauthenticatedUserUpdatingASignup()
    {
        $signup = factory(Signup::class)->create();

        $response = $this->json('PATCH', 'api/v3/signups/' . $signup->id, [
            'quantity'     => 888,
            'why_participated'  => 'new why participated',
        ]);

        $response->assertResponseStatus(401);
    }
}
