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
        // First campaign
        $firstCampaignTitle = $this->faker->sentence;
        $firstCampaignStartDate = $this->faker->date;
        $firstCampaignEndDate = $this->faker->date;

        $this->actingAsAdmin()->postJson('campaigns', [
            'internal_title' => $firstCampaignTitle,
            'start_date' => $firstCampaignStartDate,
            'end_date' => $firstCampaignEndDate,
        ]);

        // Make sure the campaign is persisted.
        $this->assertDatabaseHas('campaigns', [
            'internal_title' => $firstCampaignTitle,
            'start_date' => $firstCampaignStartDate,
            'end_date' => $firstCampaignEndDate,
        ]);

        // Try to create a second campaign with the same title and make sure it doesn't duplicate.
        $this->actingAsAdmin()->postJson('campaigns', [
            'internal_title' => $firstCampaignTitle,
            'start_date' => $this->faker->dateTime,
            'end_date' => $this->faker->dateTime,
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
     * Test that a GET request to /api/v3/campaigns/:campaign_id returns the intended campaign.
     *
     * GET /api/v3/campaigns/:campaign_id
     * @return void
     */
    public function testCampaignShow()
    {
        // Create 5 campaigns
        factory(Campaign::class, 5)->create();

        // Create 1 specific campaign to search for
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
        // Create a campaign to update
        $campaign = factory(Campaign::class)->create();

        // Update the title
        $this->actingAsAdmin()->patchJson('campaigns/' . $campaign->id, [
            'internal_title' => 'Updated Title',
        ]);

        // Make sure the campaign update is persisted.
        $response = $this->getJson('api/v3/campaigns/' . $campaign->id);
        $decodedResponse = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals('Updated Title', $decodedResponse['data']['internal_title']);
    }
}
