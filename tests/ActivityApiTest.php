<?php

use Rogue\Models\Signup;

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
    public function testActivityIndexWithCampaignIdQuery()
    {
        $signup = Signup::create([
            'northstar_id' => '1234',
            'campaign_id' => '47',
            'campaign_run_id' => '1771',
        ]);
        dd($signup);

        // $this->asUserUsingLegacyAuth($user)->withLegacyApiKeyScopes(['user'])->get('v1/signups?users='.$user->_id.','.$user2->_id);


        $response = $this->json('GET', $this->activityApiUrl . '?filter[campaign_id]=' . $signup->campaign_id);

        $this->assertResponseStatus(200);

        $response = $this->decodeResponseJson();
        dd($response);
        // $this->assertEquals('47', $response['meta']['pagination']['current_page']);
    }
}
