<?php

namespace Tests\Http;

use Tests\TestCase;
use Rogue\Models\Campaign;

class CampaignTest extends Testcase
{
    /**
     * Test that a POST request to /campaigns creates a new campaign.
     *
     * POST /campaigns
     * @return void
     */
    public function testCreatingACampaign()
    {
        // Create a campaign.
        $firstCampaignTitle = $this->faker->sentence;
        $firstCampaignStartDate = $this->faker->date($format = 'm/d/Y');
        // Make sure the end date is after the start date.
        $firstCampaignEndDate = date('m/d/Y', strtotime('+3 months', strtotime($firstCampaignStartDate)));

        $this->actingAsAdmin()->postJson('campaign-ids', [
            'internal_title' => $firstCampaignTitle,
            'cause' => ['animal-welfare'],
            'impact_doc' => 'https://www.google.com',
            'start_date' => $firstCampaignStartDate,
            'end_date' => $firstCampaignEndDate,
        ]);

        // Make sure the campaign is persisted.
        $this->assertDatabaseHas('campaigns', [
            'internal_title' => $firstCampaignTitle,
        ]);

        // Try to create a second campaign with the same title and make sure it doesn't duplicate.
        $this->actingAsAdmin()->postJson('campaign-ids', [
            'internal_title' => $firstCampaignTitle,
        ]);

        $response = $this->getJson('api/v3/campaigns');
        $decodedResponse = $response->decodeResponseJson();

        $this->assertEquals(1, $decodedResponse['meta']['pagination']['count']);
    }

    /**
     * Test that a GET request to /api/v3/campaigns returns an index of all campaigns.
     *
     * GET /api/v3/campaigns
     * @return void
     */
    public function testCampaignIndex()
    {
        factory(Campaign::class, 5)->create();

        $response = $this->getJson('api/v3/campaigns');
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(5, $decodedResponse['meta']['pagination']['count']);
    }

    /**
     * Test that we can filter open or closed campaigns.
     *
     * GET /api/v3/campaigns
     * @return void
     */
    public function testFilteredCampaignIndex()
    {
        factory(Campaign::class, 5)->create();
        factory(Campaign::class, 'closed', 3)->create();

        $response = $this->getJson('api/v3/campaigns?filter[is_open]=true');
        $decodedResponse = $response->decodeResponseJson();
        $this->assertEquals(5, $decodedResponse['meta']['pagination']['count']);

        $response = $this->getJson('api/v3/campaigns?filter[is_open]=false');
        $decodedResponse = $response->decodeResponseJson();
        $this->assertEquals(3, $decodedResponse['meta']['pagination']['count']);
    }

    /**
     * Test that we can use cursor pagination.
     *
     * GET /api/v3/campaigns
     * @return void
     */
    public function testCampaignCursor()
    {
        $campaigns = factory(Campaign::class, 5)->create();

        // First, let's get the three campaigns with the most pending posts:
        $endpoint = 'api/v3/campaigns?limit=3';
        $response = $this->withAdminAccessToken()->getJson($endpoint);

        $response->assertSuccessful();
        $json = $response->json();
        $this->assertCount(3, $json['data']);

        // Then, we'll use the last post's cursor to fetch the remaining two:
        $lastCursor = $json['data'][2]['cursor'];
        $response = $this->withAdminAccessToken()->getJson($endpoint . '&after=' . $lastCursor);

        $response->assertSuccessful();
        $json = $response->json();
        $this->assertCount(2, $json['data']);
    }

    /**
     * Test that a GET request to /api/v3/campaigns/:campaign_id returns the intended campaign.
     *
     * GET /api/v3/campaigns/:campaign_id
     * @return void
     */
    public function testCampaignShow()
    {
        // Create 5 campaigns.
        factory(Campaign::class, 5)->create();

        // Create 1 specific campaign to search for.
        $campaign = factory(Campaign::class)->create();

        $response = $this->getJson('api/v3/campaigns/' . $campaign->id);
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals($campaign->id, $decodedResponse['data']['id']);
    }

    /**
     * Test that a PATCH request to /campaigns/:campaign_id updates a campaign.
     *
     * PATCH /campaigns/:campaign_id
     * @return void
     */
    public function testUpdatingACampaign()
    {
        // Create a campaign to update.
        $campaign = factory(Campaign::class)->create();

        // Update the title.
        $response = $this->actingAsAdmin()->patch('campaign-ids/' . $campaign->id, [
            'internal_title' => 'Updated Title',
            'impact_doc' => 'https://www.bing.com/',
            'cause' => ['lgbtq-rights'],
            'start_date' => '1/1/2018',
        ]);

        // Make sure the campaign update is persisted.
        $response = $this->getJson('api/v3/campaigns/' . $campaign->id);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'internal_title' => 'Updated Title',
                'cause' => ['lgbtq-rights'],
                'start_date' => '2018-01-01T00:00:00+00:00',
            ],
        ]);
    }

    /**
     * Test that a DELETE request to /campaigns/:campaign_id deletes a campaign.
     *
     * DELETE /campaigns/:campaign_id
     * @return void
     */
    public function testDeleteACampaign()
    {
        // Create a campaign to delete.
        $campaign = factory(Campaign::class)->create();

        // Delete the campaign.
        $this->actingAsAdmin()->deleteJson('campaign-ids/' . $campaign->id);

        // Make sure the campaign is deleted.
        $response = $this->getJson('api/v3/campaigns/' . $campaign->id);
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(404);
        $this->assertEquals('That resource could not be found.', $decodedResponse['message']);
    }
}
