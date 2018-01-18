<?php

namespace Tests\Http\Api;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\Signup;
use DoSomething\Gateway\Northstar;
use DoSomething\Gateway\Resources\NorthstarUser;

class ActivityApiTest extends TestCase
{
    /**
     * Test for retrieving activity.
     *
     * GET /activity?limit=8
     * @return void
     */
    public function testActivityIndex()
    {
        factory(Signup::class, 10)->create();

        $response = $this->getJson('api/v2/activity');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'signup_id',
                    'northstar_id',
                    'campaign_id',
                    'campaign_run_id',
                    'quantity',
                    'why_participated',
                    'signup_source',
                    'created_at',
                    'updated_at',
                    'posts' => [],
                ],
            ],
            'meta' => [
                'pagination' => [
                    'total',
                    'count',
                    'per_page',
                    'total_pages',
                    'links',
                ],
            ],
        ]);
    }

    /**
     * Test for retrieving a user's activity with limit query param.
     *
     * GET /activity?limit=8
     * @return void
     */
    public function testActivityIndexWithLimitQuery()
    {
        factory(Signup::class, 10)->create();

        $response = $this->getJson('api/v2/activity?limit=8');
        $response->assertStatus(200);

        $json = $response->json();
        $this->assertCount(8, $json['data']);
        $this->assertNotEmpty($json['meta']['pagination']['links']['next']);
        $this->assertEquals(1, $json['meta']['pagination']['current_page']);
    }

    /**
     * Test for retrieving a user's activity with page query param.
     *
     * GET /activity?page=3
     * @return void
     */
    public function testActivityIndexWithPageQuery()
    {
        factory(Signup::class, 22)->create();

        $response = $this->getJson('api/v2/activity?page=2');

        $response->assertStatus(200);

        // By default, we show 20 posts per page, so we should see 2 here.
        $json = $response->json();
        $this->assertCount(2, $json['data']);
    }

    /**
     * Test for retrieving a user's activity with campaign_id query param.
     *
     * GET /activity?filter[]=
     * @return void
     */
    public function testActivityIndexWithFilter()
    {
        factory(Signup::class, 3)->create(['campaign_id' => 17]);
        factory(Signup::class, 5)->create();

        $response = $this->getJson('api/v2/activity?filter[campaign_id]=17');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'campaign_id' => 17,
                ],
            ],
            'meta' => [
                'pagination' => [
                    'current_page' => 1,
                    'count' => 3,
                ],
            ],
        ]);
    }

    /**
     * Test for retrieving a user's activity with multiple filters.
     *
     * GET /activity?filter[]=&filter[]=
     * @return void
     */
    public function testActivityIndexWithMultipleFilters()
    {
        factory(Signup::class, 3)->create(['campaign_id' => 14, 'campaign_run_id' => 132]);
        factory(Signup::class, 5)->create();

        $response = $this->getJson('api/v2/activity?filter[campaign_id]=14&filter[campaign_run_id]=132');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'campaign_id' => 14,
                    'campaign_run_id' => 132,
                    // ...
                ],
            ],
        ]);
    }

    /**
     * Test for retrieving a user's activity with combination of query params
     * where we expect nothing to be returned.
     *
     * GET /activity?filter[campaign_run_id]=479,49&filter[campaign_run_id]=z
     * @return void
     */
    public function testActivityIndexWithFilterAndNoResults()
    {
        $signups = factory(Signup::class, 2)->create();

        $response = $this->getJson('api/v2/activity?filter[campaign_id]=' . $signups[0]->campaign_id . ',' . $signups[1]->campaign_id . '&filter[campaign_run_id]=z');

        $response->assertStatus(200);
        $response->assertJson(['data' => []]);
    }

    /**
     * Test for retrieving a user's activity with included user info.
     *
     * GET /activity?include=user
     * @return void
     */
    public function testActivityIndexWithIncludedUser()
    {
        factory(Signup::class, 10)->create();

        $this->mock(Northstar::class)
            ->shouldReceive('asClient')
            ->andReturnSelf()
            ->shouldReceive('getUser')
            ->andReturnUsing(function ($field, $id) {
                return new NorthstarUser([
                    'id' => $id,
                    'first_name' => $this->faker->firstName,
                ]);
            });

        $response = $this->getJson('api/v2/activity?include=user');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'user' => [
                        'data' => [
                            'first_name',
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Test for retrieving a user's activity with the updated_at query param.
     *
     * GET /activity?filter[updated_at]=2017-05-25 20:14:48
     * @return void
     */
    public function testActivityIndexWithUpdatedAtQuery()
    {
        $this->mockTime('8/01/2017 15:00:00');

        // Create two signups and two posts. Associate the signups and posts respectively.
        $firstSignup = factory(Signup::class)->create();
        $firstSignup->posts()->save(factory(Post::class)->make());

        // We'll create the second signup a minute later...
        $this->mockTime('8/01/2017 15:01:00');

        $secondSignup = factory(Signup::class)->create();
        $secondSignup->posts()->save(factory(Post::class)->make());

        $response = $this->getJson('api/v2/activity?filter[updated_at]=' . $firstSignup->updated_at);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'signup_id' => $secondSignup->id,
                ],
            ],
        ]);
    }

    /**
     * Test for retrieving activity using simplePagination
     *
     * GET /activity?pagination=cursor
     * @return void
     */
    public function testActivityIndexWithFastPagination()
    {
        factory(Signup::class, 10)->create();

        $response = $this->getJson('api/v2/activity?pagination=cursor');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'signup_id',
                    'northstar_id',
                    'campaign_id',
                    'campaign_run_id',
                    'quantity',
                    'why_participated',
                    'signup_source',
                    'created_at',
                    'updated_at',
                    'posts' => [],
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
     * Test to make sure we are returning quantity correctly.
     * Quantity is either summed total of quantity across all posts under a signup
     * or quanttiy under signup if it is still on signup record.
     *
     * GET /activity
     * @return void
     */
    public function testQuantityOnActivityIndex()
    {
        // Create one signup with no quantity.
        $firstSignup = factory(Signup::class)->create();

        // Create another signup with three posts with quantities.
        $secondSignup = factory(Signup::class)->create();

        $posts = factory(Post::class, 3)->create(['signup_id' => $secondSignup->id]);

        // Update the signup quantity to equal the sum of post quantities.
        $secondSignup->quantity = $secondSignup->getQuantity();
        $secondSignup->save();

        $response = $this->getJson('api/v2/activity');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'quantity' => $firstSignup->quantity,
                    // ...
                ],
                [
                    'quantity' => $secondSignup->quantity,
                    // ...
                ],
            ],
        ]);
    }

    /**
     * Test for retrieving a user's activity with northstar_id and campaign_run_id query params.
     * This test is to ensure we are not breaking the Gambit flow when it checks to see if a signup exists.
     *
     * GET /activity?filter[]=
     * @return void
     */
    public function testActivityIndexWithNorthstarIdAndCampaignRunIdFilters()
    {
        $signup = factory(Signup::class)->create(['northstar_id' => 17, 'campaign_run_id' => 143]);
        factory(Signup::class, 5)->create();

        $response = $this->getJson('api/v2/activity?filter[northstar_id]=17&filter[campaign_run_id]=143');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'signup_id' => $signup->id,
                    'northstar_id' => "17",
                    'campaign_run_id' => "143",
                ],
            ],
            'meta' => [
                'pagination' => [
                    'current_page' => 1,
                    'count' => 1,
                ],
            ],
        ]);
    }
}
