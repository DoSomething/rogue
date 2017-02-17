<?php

class ActivityApiTest extends TestCase
{
    /*
     * Base URL for the Api.
     */
    protected $activityApiUrl = 'api/v2/activity';

    /**
     * Test for retrieving a user's activity with limit query param.
     *
     *  GET /activity?limit=8
     * @return void
     */
    public function testActivityIndexWithLimitQuery()
    {
        $response = $this->json('GET', $this->activityApiUrl . '?limit=8');

        $this->assertResponseStatus(200);

        $response = $this->decodeResponseJson();

        $this->assertEquals(8, $response['meta']['pagination']['per_page']);
    }

    /**
     * Test for retrieving a user's activity with page query param.
     *
     *  GET /activity?page=3
     * @return void
     */
    public function testActivityIndexWithPageQuery()
    {
        $response = $this->json('GET', $this->activityApiUrl . '?page=3');

        $this->assertResponseStatus(200);

        $response = $this->decodeResponseJson();

        $this->assertEquals(3, $response['meta']['pagination']['current_page']);
    }

    /**
     * Test for retrieving a user's activity with campaign_id query param.
     *
     *  GET /activity?filter[campaign_id]=47
     * @return void
     */
    // public function testActivityIndexWithCampaignIdQuery()
    // {
    //     // do we need to make a signup with this campaign?
    //     $response = $this->json('GET', $this->activityApiUrl . '?filter[campaign_id]=3');

    //     $this->assertResponseStatus(200);

    //     $response = $this->decodeResponseJson();

    //     $this->assertEquals(3, $response['meta']['pagination']['current_page']);
    // }
}
