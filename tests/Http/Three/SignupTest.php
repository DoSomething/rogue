<?php

namespace Tests\Http\Three;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\User;
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
        $northstarId = $this->faker->northstar_id;
        $campaignId = str_random(22);
        $campaignRunId = $this->faker->randomNumber(4);

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignup');

        $response = $this->withAccessToken($northstarId, 'user', ['activity', 'write'])->postJson('api/v3/signups', [
            'campaign_id' => $campaignId,
            'campaign_run_id' => $campaignRunId,
            'details' => 'affiliate-messaging',
        ]);

        // Make sure we get the 201 Created response
        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'northstar_id' => $northstarId,
                'campaign_id' => $campaignId,
                'campaign_run_id' => $campaignRunId,
                'quantity' => null,
                'source' => 'phpunit',
                'why_participated' => null,
            ],
        ]);

        // Make sure the signup is persisted.
        $this->assertDatabaseHas('signups', [
            'northstar_id' => $northstarId,
            'campaign_id' => $campaignId,
            'campaign_run_id' => $campaignRunId,
            'quantity' => null,
            'details' => 'affiliate-messaging',
        ]);
    }

    /**
     * Test that a POST request to /signups doesn't create a new signup without activity and write scopes.
     *
     * POST /api/v3/signups
     * @return void
     */
    public function testCreatingASignupWithoutRequiredScopes()
    {
        $northstarId = $this->faker->northstar_id;
        $campaignId = str_random(22);
        $campaignRunId = $this->faker->randomNumber(4);

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignup');

        $response = $this->postJson('api/v3/signups', [
            'campaign_id' => $campaignId,
            'campaign_run_id' => $campaignRunId,
            'details' => 'affiliate-messaging',
        ]);

        // Make sure we get the 401 Unauthenticated response
        $response->assertStatus(401);
        $this->assertEquals($response->decodeResponseJson()['message'], 'Unauthenticated.');

        $response = $this->withAccessToken($northstarId, 'user', ['activity'])->postJson('api/v3/signups', [
            'campaign_id' => $campaignId,
            'campaign_run_id' => $campaignRunId,
            'details' => 'affiliate-messaging',
        ]);

        // Make sure we get the 403 Forbidden response
        $response->assertStatus(403);
        $this->assertEquals($response->decodeResponseJson()['message'], 'Requires a token with the following scopes: write');
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

        $response = $this->withAccessToken($signup->northstar_id, 'user', ['activity', 'write'])->postJson('api/v3/signups', [
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'source' => 'the-fox-den',
            'details' => 'affiliate-messaging',
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
     * Test for retrieving all signups as non-admin and non-owner.
     * Non-admins/non-owners should not see why_participated, source, or details in response.
     *
     * GET /api/v3/signups
     * @return void
     */
    public function testSignupsIndexAsNonAdminNonOwner()
    {
        factory(Signup::class, 10)->create();

        $response = $this->withAccessToken($this->randomUserId(), 'user', ['activity'])->getJson('api/v3/signups');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'northstar_id',
                    'campaign_id',
                    'campaign_run_id',
                    'quantity',
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
     * Test that user is not visible without activity scope.
     *
     * GET /api/v3/signups
     * @return void
     */
    public function testSignupsIndexWithoutRequiredScopes()
    {
        factory(Signup::class, 10)->create();

        $response = $this->getJson('api/v3/signups');

        $response->assertStatus(403);
        $response->assertEquals($response->decodeResponseJson(), 'Requires a token with the following scopes: activity');
    }

    /**
     * Test for retrieving all signups as admin.
     * Admins should see why_participated, source, and details in response.
     *
     * GET /api/v3/signups
     * @return void
     */
    public function testSignupsIndexAsAdmin()
    {
        factory(Signup::class, 10)->create();

        $response = $this->withAccessToken($this->randomUserId(), 'admin', ['activity'])->getJson('api/v3/signups');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'northstar_id',
                    'campaign_id',
                    'campaign_run_id',
                    'quantity',
                    'created_at',
                    'updated_at',
                    'why_participated',
                    'source',
                    'details',
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
     * Test for retrieving all signups as owner.
     * Signup owner should see why_participated, source, and details in response.
     *
     * GET /api/v3/signups
     * @return void
     */
    public function testSignupsIndexAsOwner()
    {
        $northstarId = $this->faker->northstar_id;
        $signups = factory(Signup::class, 10)->create([
           'northstar_id' => $northstarId,
        ]);

        $response = $this->withAccessToken($northstarId, 'user', ['activity', 'write'])->getJson('api/v3/signups');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'northstar_id',
                    'campaign_id',
                    'campaign_run_id',
                    'quantity',
                    'created_at',
                    'updated_at',
                    'why_participated',
                    'source',
                    'details',
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
     * Test for signup index with included pending post info. as non-admin/non-owner.
     * Only admins/owners should be able to see pending/rejected posts.
     *
     * GET /api/v3/signups?include=posts
     * @return void
     */
    public function testSignupIndexWithIncludedPostsAsNonAdminNonOwner()
    {
        $post = factory(Post::class)->create();
        $signup = $post->signup;

        // Test with annoymous user that no posts are returned.
        $response = $this->withAccessToken($this->randomUserId(), 'user', ['activity'])->getJson('api/v3/signups' . '?include=posts');
        $response->assertStatus(200);
        $decodedResponse = $response->decodeResponseJson();

        $this->assertEquals(true, empty($decodedResponse['data'][0]['posts']['data']));
    }

    /**
     * Test for signup index with included pending post info. as admin
     * Only admins/owners should be able to see pending/rejected posts.
     *
     * GET /api/v3/signups?include=posts
     * @return void
     */
    public function testSignupIndexWithIncludedPostsAsAdmin()
    {
        $post = factory(Post::class)->create();
        $signup = $post->signup;

        // Test with admin that posts are returned.
        $response = $this->withAccessToken($this->randomUserId(), 'admin', ['activity'])->getJson('api/v3/signups' . '?include=posts');
        $response->assertStatus(200);
        $decodedResponse = $response->decodeResponseJson();

        $this->assertEquals(false, empty($decodedResponse['data'][0]['posts']['data']));
    }

    /**
     * Test for signup index with included pending post info. as owner
     * Only admins/owners should be able to see pending/rejected posts.
     *
     * GET /api/v3/signups?include=posts
     * @return void
     */
    public function testSignupIndexWithIncludedPostsAsOwner()
    {
        $post = factory(Post::class)->create();
        $signup = $post->signup;

        // Test with admin that posts are returned.
        $response = $this->withAccessToken($post->northstar_id, 'user', ['activity'])->getJson('api/v3/signups' . '?include=posts');
        $response->assertStatus(200);
        $decodedResponse = $response->decodeResponseJson();

        $this->assertEquals(false, empty($decodedResponse['data'][0]['posts']['data']));
    }

    /**
     * Test for signup index with included user info. as admin.
     * Only admins/owners should be able to see all user info.
     *
     * GET /api/v3/signups?include=user
     * @return void
     */
    public function testSignupIndexWithIncludedUserAsAdmin()
    {
        // @TODO: revisit with this card: https://www.pivotaltracker.com/n/projects/2019429/stories/155479565
        $this->markTestIncomplete();
        // $post = factory(Post::class)->create();
        // $signup = $post->signup;

        // // Test with admin that entire user is returned.
        // $response = $this->withAdminAccessToken()->getJson('api/v3/signups' . '?include=user');
        // $response->assertStatus(200);
        // $decodedResponse = $response->decodeResponseJson();

        // $this->assertEquals(false, empty($decodedResponse['data'][0]['user']['data']['birthdate']));
    }

    /**
     * Test for signup index with included user info. as owner.
     * Only admins/owners should be able to see all user info.
     *
     * GET /api/v3/signups?include=user
     * @return void
     */
    public function testSignupIndexWithIncludedUserAsOwner()
    {
        // @TODO: revisit with this card: https://www.pivotaltracker.com/n/projects/2019429/stories/155479565
        $this->markTestIncomplete();
        // $post = factory(Post::class)->create();
        // $signup = $post->signup;

        // // Test with admin that entire user is returned.
        // $response = $this->withAccessToken($signup->northstar_id)->getJson('api/v3/signups' . '?include=user');
        // $response->assertStatus(200);
        // $decodedResponse = $response->decodeResponseJson();

        // $this->assertEquals(false, empty($decodedResponse['data'][0]['user']['data']['birthdate']));
    }

    /**
     * Test for signup index with included user info. as non-admin/non-owner.
     * Only admins/owners should be able to see all user info.
     *
     * GET /api/v3/signups?include=user
     * @return void
     */
    public function testSignupIndexWithIncludedUserAsNonAdminNonOwner()
    {
        // @TODO: revisit with this card: https://www.pivotaltracker.com/n/projects/2019429/stories/155479565
        $this->markTestIncomplete();
        // $post = factory(Post::class)->create();
        // $signup = $post->signup;

        // // Test with annoymous user that only a user's first name is returned.
        // $response = $this->getJson('api/v3/signups' . '?include=user');
        // $response->assertStatus(200);
        // $decodedResponse = $response->decodeResponseJson();

        // $this->assertEquals(true, empty($decodedResponse['data'][0]['user']['data']['birthdate']));
    }

    /**
     * Test for retrieving all signups as admin with northstar_id, campaign_id, and campaign_run_id filters (and a combinations of all).
     *
     * GET /api/v3/signups?filter[northstar_id]=56d5baa7a59dbf106b8b45aa
     * GET /api/v3/signups?filter[campaign_id]=1
     * GET /api/v3/signups?filter[campaign_run_id]=32
     * GET /api/v3/signups?filter[campaign_id]=1&filter[northstar_id]=56d5baa7a59dbf106b8b45aa
     * GET /api/v3/signups?filter[campaign_id]=1,2
     *
     * @return void
     */
    public function testSignupsIndexAsAdminWithFilters()
    {
        // @TODO: revisit with this card: https://www.pivotaltracker.com/n/projects/2019429/stories/155479565
        $this->markTestIncomplete();
        // $northstarId = $this->faker->northstar_id;
        // $campaignId = str_random(22);
        // $campaignRunId = $this->faker->randomNumber(4);

        // // Create two signups
        // factory(Signup::class, 2)->create([
        //    'northstar_id' => $northstarId,
        //    'campaign_id' => $campaignId,
        //    'campaign_run_id' => $campaignRunId,
        // ]);

        // // Create three more signups with different northstar_id, campaign_id, and campaign_run_id
        // $secondNorthstarId = $this->faker->northstar_id;
        // $secondCampaignId = str_random(22);
        // $secondCampaignRunId = $this->faker->randomNumber(4);

        // factory(Signup::class, 3)->create([
        //    'northstar_id' => $secondNorthstarId,
        //    'campaign_id' => $secondCampaignId,
        //    'campaign_run_id' => $secondCampaignRunId,
        // ]);

        // // Filter by northstar_id
        // $response = $this->withAdminAccessToken()->getJson('api/v3/signups?filter[northstar_id]=' . $northstarId);
        // $decodedResponse = $response->decodeResponseJson();

        // $response->assertStatus(200);

        // // Assert only 2 signups are returned
        // $this->assertEquals(2, $decodedResponse['meta']['cursor']['count']);

        // // Filter by campaign_id
        // $response = $this->withAdminAccessToken()->getJson('api/v3/signups?filter[campaign_id]=' . $secondCampaignId);
        // $decodedResponse = $response->decodeResponseJson();

        // $response->assertStatus(200);

        // // Assert only 3 signups are returned
        // $this->assertEquals(3, $decodedResponse['meta']['cursor']['count']);

        // // Filter by campaign_run_id
        // $response = $this->withAdminAccessToken()->getJson('api/v3/signups?filter[campaign_run_id]=' . $campaignRunId);
        // $decodedResponse = $response->decodeResponseJson();

        // $response->assertStatus(200);

        // // Assert only 2 signups are returned
        // $this->assertEquals(2, $decodedResponse['meta']['cursor']['count']);

        // // Filter by campaign_run_id and northstar_id
        // $response = $this->withAdminAccessToken()->getJson('api/v3/signups?filter[campaign_run_id]=' . $campaignRunId . '&filter[northstar_id]=' . $northstarId);
        // $decodedResponse = $response->decodeResponseJson();

        // $response->assertStatus(200);

        // // Assert only 2 signups are returned
        // $this->assertEquals(2, $decodedResponse['meta']['cursor']['count']);

        // // Filter by multiple campaign_run_id
        // $response = $this->withAdminAccessToken()->getJson('api/v3/signups?filter[campaign_run_id]=' . $campaignRunId . ',' . $secondCampaignRunId);
        // $decodedResponse = $response->decodeResponseJson();

        // $response->assertStatus(200);

        // // Assert all signups are returned
        // $this->assertEquals(5, $decodedResponse['meta']['cursor']['count']);
    }

    /**
     * Test for retrieving all signups as admin filtering by quantity (in ascending or descending order).
     *
     * GET /api/v3/signups?orderBy=quantity,desc
     * GET /api/v3/signups?orderBy=quantity,asc
     *
     * @return void
     */
    public function testSignupsIndexAsAdminWithOrderByQuantityFilter()
    {
        // Create 5 signups with different quantities
        $signups = factory(Signup::class, 5)->create();
        $quantity = 1;

        foreach ($signups as $signup) {
            $signup->quantity = $quantity++;
            $signup->save();
        }

        // Order results by descending quantity
        $response = $this->withAccessToken($this->randomUserId(), 'admin', ['activity'])->getJson('api/v3/signups?orderBy=quantity,desc');
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);

        // Assert results are returned in descending order.
        $this->assertEquals(5, $decodedResponse['data'][0]['quantity']);
        $this->assertEquals(4, $decodedResponse['data'][1]['quantity']);

        // Order results by ascending quantity
        $response = $this->withAccessToken($this->randomUserId(), 'admin', ['activity'])->getJson('api/v3/signups?orderBy=quantity,asc');
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);

        // Assert results are returned in ascending order.
        $this->assertEquals(1, $decodedResponse['data'][0]['quantity']);
        $this->assertEquals(2, $decodedResponse['data'][1]['quantity']);
    }

    /**
     * Test for retrieving a specific signup as non-admin and non-owner.
     *
     * GET /api/v3/signups/:signup_id
     * @return void
     */
    public function testSignupShowAsNonAdminNonOwner()
    {
        $signup = factory(Signup::class)->create();
        $response = $this->withAccessToken($this->randomUserId(), 'user', ['activity'])->getJson('api/v3/signups/' . $signup->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'northstar_id',
                'campaign_id',
                'campaign_run_id',
                'quantity',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    /**
     * Test for retrieving a specific signup without activity scope.
     *
     * GET /api/v3/signups/:signup_id
     * @return void
     */
    public function testSignupShowWithoutRequiredScopes()
    {
        $signup = factory(Signup::class)->create();
        $response = $this->getJson('api/v3/signups/' . $signup->id);

        $response->assertStatus(403);
    }

    /**
     * Test for retrieving a specific signup as admin.
     *
     * GET /api/v3/signups/:signup_id
     * @return void
     */
    public function testSignupShowAsAdmin()
    {
        $signup = factory(Signup::class)->create();
        $response = $this->withAccessToken($this->randomUserId(), 'admin', ['activity'])->getJson('api/v3/signups/' . $signup->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'northstar_id',
                'campaign_id',
                'campaign_run_id',
                'quantity',
                'created_at',
                'updated_at',
                'why_participated',
                'source',
                'details',
            ],
        ]);
    }

    /**
     * Test for retrieving a specific signup as owner.
     *
     * GET /api/v3/signups/:signup_id
     * @return void
     */
    public function testSignupShowAsOwner()
    {
        $signup = factory(Signup::class)->create();
        $response = $this->withAccessToken($signup->northstar_id, 'user', ['activity'])->getJson('api/v3/signups/' . $signup->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'northstar_id',
                'campaign_id',
                'campaign_run_id',
                'quantity',
                'created_at',
                'updated_at',
                'why_participated',
                'source',
                'details',
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

        $response = $this->withAccessToken($this->randomUserId(), 'admin', ['activity', 'write'])->deleteJson('api/v3/signups/' . $signup->id);

        $response->assertStatus(200);

        // Make sure that the signup's deleted_at gets persisted in the database.
        $this->assertEquals($signup->fresh()->deleted_at->toTimeString(), '14:00:00');
    }

    /**
     * Test that a signup cannot be deleted without required scopes
     *
     * @return void
     */
    public function testDeletingASignupWithoutRequiredScopes()
    {
        // $signup = factory(Signup::class)->create();

        // $response = $this->deleteJson('api/v3/signups/' . $signup->id);

        // $response->assertStatus(401);
        // // $response->assertEquals($response->decodeResponseJson()['message'], 'Unauthenticated.');

        // $response = $this->withAccessToken($this->randomUserId(), 'admin', ['activity'])->deleteJson('api/v3/signups/' . $signup->id);

        // dd($response->decodeResponseJson());
        $this->markTestIncomplete();
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

        $response = $this->withAccessToken($this->randomUserId(), 'admin', ['activity', 'write'])->patchJson('api/v3/signups/' . $signup->id, [
            'why_participated'  => 'new why participated',
        ]);

        $response->assertStatus(200);

        // Make sure that the signup's new why_participated gets persisted in the database.
        $this->assertEquals($signup->fresh()->why_participated, 'new why participated');
    }

    /**
     * Test that a signup cannot be updated without the required scopes.
     *
     * PATCH /api/v3/signups/186
     * @return void
     */
    public function testUpdatingASignupWithoutRequiredScopes()
    {
        $signup = factory(Signup::class)->create();

        $response = $this->patchJson('api/v3/signups/' . $signup->id, [
            'why_participated'  => 'new why participated',
        ]);

        $response->assertStatus(401);
        // $response->assertEquals($response->decodeResponseJson()['message'], 'Unauthenticated.');

        $response = $this->withAccessToken($this->randomUserId(), 'admin', ['activity'])->patchJson('api/v3/signups/' . $signup->id, [
            'why_participated'  => 'new why participated',
        ]);
        $response->assertStatus(200);
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

        $response = $this->withAccessToken($this->randomUserId(), 'admin', ['activity', 'write'])->patchJson('api/v3/signups/' . $signup->id);

        $response->assertStatus(422);
    }

    /**
     * Test that a non-admin or user that doesn't own the signup can't update signup.
     *
     * @return void
     */
    public function testUnauthenticatedUserUpdatingASignup()
    {
        $user = factory(User::class)->create();
        $signup = factory(Signup::class)->create();

        $response = $this->withAccessToken($user->id, 'user', ['activity', 'write'])->patchJson('api/v3/signups/' . $signup->id, [
            'why_participated' => 'new why participated',
        ]);

        $response->assertStatus(403);

        $json = $response->json();

        $this->assertEquals('You don\'t have the correct role to update this signup!', $json['message']);
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

        $response = $this->withAccessToken($this->randomUserId(), 'user', ['activity'])->getJson('api/v3/signups');

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
