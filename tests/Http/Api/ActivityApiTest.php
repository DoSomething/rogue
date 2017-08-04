<?php

use Rogue\Models\Signup;
use Rogue\Models\Post;

class ActivityApiTest extends TestCase
{
    /**
     * Test for retrieving a user's activity with limit query param.
     *
     * GET /activity?limit=8
     * @return void
     */
    public function testActivityIndexWithLimitQuery()
    {
        $this->get('api/v2/activity?limit=8');
        $this->assertResponseStatus(200);

        $this->seeJsonSubset([
            'meta' => [
                'pagination' => [
                    'per_page' => 8
                ],
            ]
        ]);
    }

    /**
     * Test for retrieving a user's activity with page query param.
     *
     * GET /activity?page=3
     * @return void
     */
    public function testActivityIndexWithPageQuery()
    {
        $this->get('api/v2/activity?page=3');

        $this->assertResponseStatus(200);

        $this->seeJsonSubset([
            'meta' => [
                'pagination' => [
                    'current_page' => 3
                ],
            ]
        ]);
    }

    /**
     * Test for retrieving a user's activity with campaign_id query param.
     *
     * GET /activity?filter[campaign_id]=47
     * @return void
     */
    public function testActivityIndexWithCampaignIdQuery()
    {
        $signup = factory(Signup::class)->create();

        $this->get('api/v2/activity?filter[campaign_id]=' . $signup->campaign_id);

        $this->assertResponseStatus(200);
        $this->seeJsonSubset([
            'data' => [
                [
                    'campaign_id' => $signup->campaign_id
                ],
            ],
        ]);
    }

    /**
     * Test for retrieving a user's activity with campaign_run_id query param.
     *
     * GET /activity?filter[campaign_run_id]=479
     * @return void
     */
    public function testActivityIndexWithCampaignRunIdQuery()
    {
        $signup = factory(Signup::class)->create();

        $this->get('api/v2/activity?filter[campaign_run_id]=' . $signup->campaign_run_id);

        $this->assertResponseStatus(200);
        $this->seeJsonSubset([
            'data' => [
                [
                    'campaign_run_id' => $signup->campaign_run_id
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
    public function testActivityIndexWithMixedQueryReturnsNoResults()
    {
        $signups = factory(Signup::class, 2)->create();

        $this->get('api/v2/activity?filter[campaign_id]=' . $signups[0]->campaign_id . ',' . $signups[1]->campaign_id . '&filter[campaign_run_id]=z');

        $this->assertResponseStatus(200);
        $this->seeJsonSubset([
            'meta' => [
                'pagination' => [
                    'total' => 0
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
