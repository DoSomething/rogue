<?php

namespace Tests\Http\Api;

use Rogue\Models\Post;
use Rogue\Models\Signup;
use Tests\BrowserKitTestCase;
use DoSomething\Gateway\Northstar;
use DoSomething\Gateway\Resources\NorthstarUser;

class ActivityApiTest extends BrowserKitTestCase
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

        $this->get('api/v2/activity');

        $this->assertResponseStatus(200);
        $this->seeJsonStructure([
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
                    'current_page',
                    'total_pages',
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

        $this->get('api/v2/activity?limit=8');
        $this->assertResponseStatus(200);

        $response = $this->decodeResponseJson();
        $this->assertCount(8, $response['data']);
        $this->assertEquals(2, $response['meta']['pagination']['total_pages']);
        $this->assertNotEmpty($response['meta']['pagination']['links']['next']);
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

        $this->get('api/v2/activity?page=2');

        $this->assertResponseStatus(200);

        // By default, we show 20 posts per page, so we should see 2 here.
        $response = $this->decodeResponseJson();
        $this->assertCount(2, $response['data']);
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

        $this->get('api/v2/activity?filter[campaign_id]=17');

        $this->assertResponseStatus(200);
        $this->seeJsonSubset([
            'data' => [
                [
                    'campaign_id' => 17,
                ],
            ],
            'meta' => [
                'pagination' => [
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

        $this->get('api/v2/activity?filter[campaign_id]=14&filter[campaign_run_id]=132');

        $this->assertResponseStatus(200);
        $this->seeJsonSubset([
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

        $this->get('api/v2/activity?filter[campaign_id]=' . $signups[0]->campaign_id . ',' . $signups[1]->campaign_id . '&filter[campaign_run_id]=z');

        $this->assertResponseStatus(200);
        $this->seeJsonSubset(['data' => []]);
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
            ->shouldReceive('getUser')
            ->andReturnUsing(function ($field, $id) {
                return new NorthstarUser([
                    'id' => $id,
                    'first_name' => $this->faker->firstName,
                ]);
            });

        $this->get('api/v2/activity?include=user');
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
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
        $firstPost = factory(Post::class)->create();
        $firstPost->signup()->associate($firstSignup);

        // We'll create the second signup a minute later...
        $this->mockTime('8/01/2017 15:01:00');

        $secondSignup = factory(Signup::class)->create();
        $secondPost = factory(Post::class)->create();
        $secondPost->signup()->associate($secondSignup);

        $this->get('api/v2/activity?filter[updated_at]=' . $firstSignup->updated_at);

        $this->assertResponseStatus(200);
        $this->seeJsonSubset([
            'data' => [
                [
                    'signup_id' => $secondSignup->id,
                ],
            ],
        ]);
    }
}
