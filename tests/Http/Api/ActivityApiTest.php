<?php

use Carbon\Carbon;
use Rogue\Models\Signup;
use Rogue\Models\Post;

class ActivityApiTest extends TestCase
{
    /*
     * Base URL for the Api.
     */
    protected $activityApiUrl = 'api/v2/activity';

    /**
     * Test for retrieving a user's activity with limit query param.
     *
     * GET /activity?limit=8
     * @return void
     */
    public function testActivityIndexWithLimitQuery()
    {
        $this->json('GET', $this->activityApiUrl . '?limit=8');
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
        $this->json('GET', $this->activityApiUrl . '?page=3');

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

        $this->json('GET', $this->activityApiUrl . '?filter[campaign_id]=' . $signup->campaign_id);

        $this->assertResponseStatus(200);

        $this->seeJsonSubset([
            'data' => [
                0 => [
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

        $this->json('GET', $this->activityApiUrl . '?filter[campaign_run_id]=' . $signup->campaign_run_id);

        $this->assertResponseStatus(200);

        $this->seeJsonSubset([
            'data' => [
                0 => [
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
        $firstSignup = factory(Signup::class)->create();

        $secondSignup = factory(Signup::class)->create();

        $this->json('GET', $this->activityApiUrl . '?filter[campaign_id]=' . $firstSignup->campaign_id . ',' . $secondSignup->campaign_id . '&filter[campaign_run_id]=z');

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
        Carbon::setTestNow(new Carbon('8/01/2017 15:00:00'));

        // Create two signups and two posts. Associate the signups and posts respectively.
        $firstSignup = factory(Signup::class)->create();
        $firstPost = factory(Post::class)->create();
        $firstPost->signup()->associate($firstSignup);
        $firstPost->save();

        // Make another signup and post later on.
        Carbon::setTestNow(new Carbon('8/01/2017 15:01:00'));

        $secondSignup = factory(Signup::class)->create();
        $secondPost = factory(Post::class)->create();
        $secondPost->signup()->associate($secondSignup);
        $secondPost->save();

        $this->json('GET', $this->activityApiUrl . '?filter[updated_at]=' . $firstSignup->updated_at);

        $this->assertResponseStatus(200);
        $this->seeJsonSubset([
            'data' => [
                0 => [
                    'signup_id' => $secondSignup->id,
                ],
            ],
        ]);

    }
}
