<?php

use Rogue\Models\Signup;
use Rogue\Models\Event;
use Faker\Generator;

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
        $event = factory(Event::class)->create();

        $signup = $this->createTestSignup($event);

        $event->signup_id = $signup->id;
        $event->save();

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
        $event = factory(Event::class)->create();

        $signup = $this->createTestSignup($event);

        $event->signup_id = $signup->id;
        $event->save();

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
        $firstEvent = factory(Event::class)->create();

        $firstSignup = $this->createTestSignup($firstEvent);

        $firstEvent->signup_id = $firstSignup->id;
        $firstEvent->save();

        $secondEvent = factory(Event::class)->create();

        $secondSignup = $this->createTestSignup($secondEvent);

        $secondEvent->signup_id = $secondSignup->id;
        $secondEvent->save();

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
}
