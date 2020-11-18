<?php

namespace Tests\Unit\Models;

use Rogue\Models\Action;
use Rogue\Models\Campaign;
use Tests\TestCase;

class CampaignTest extends TestCase
{
    /**
     * Test expected payload for Laravel Scout / Algolia.
     *
     * @return void
     */
    public function testToSearchableArray()
    {
        $campaign = factory(Campaign::class)->create();
        $action = factory(Action::class)->create([
            'campaign_id' => $campaign->id,
        ]);

        $searchableArray = $campaign->toSearchableArray();

        // The Campaign's Actions should be in the payload.
        $this->assertEquals(
            $searchableArray['actions'][0]['name'],
            $action->name,
        );

        // The date fields should be transformed to a UNIX timestamp.
        $this->assertEquals(
            $searchableArray['start_date'],
            $campaign->start_date->timestamp,
        );
        $this->assertEquals(
            $searchableArray['end_date'],
            $campaign->end_date->timestamp,
        );

        // There should be computed boolean attributes determining:
        // - if the campaign is a Website Campaign
        // - if the campaign is evergreen (has no end date)
        //
        // With a non-populated contentful_campaign_id:
        $this->assertEquals($searchableArray['has_website'], false);
        // With a populated end_date:
        $this->assertEquals($searchableArray['is_evergreen'], false);

        // With a populated contentful_campaign_id and no end_date.
        $evergreenWebsiteCampaign = factory(Campaign::class)->create([
            'contentful_campaign_id' => '123',
            'end_date' => null,
        ]);

        $this->assertEquals(
            $evergreenWebsiteCampaign->toSearchableArray()['has_website'],
            true,
        );
        $this->assertEquals(
            $evergreenWebsiteCampaign->toSearchableArray()['is_evergreen'],
            true,
        );
    }
}
