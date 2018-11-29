<?php

namespace Tests\Console;

use Carbon\Carbon;
use Tests\TestCase;
use Rogue\Models\Campaign;

class ImportLegacyCampaignsCommandTest extends TestCase
{
    public function testImportingLegacyCampaigns()
    {
        // Run the legacy campaign import command
        $this->artisan('rogue:ashescampaignsimport', ['path' => 'tests/Console/example-legacy-campaigns.csv']);

        // See that campaigns with no campaign_id have their run_id as the id in Rogue.
        $this->assertDatabaseHas('campaigns', [
            'id' => 6850,
            'internal_title' => 'DoSomething App run 1',
            'cause' => null,
            'secondary_causes' => null,
            'impact_doc' => 'https://www.google.com/',
            'campaign_run_id' => null,
            'start_date' => '2016-04-28 00:00:00',
            'end_date' => null,
            'created_at' => '2016-04-28 16:31:34',
            'updated_at' => '2016-04-28 16:31:34',
        ]);

        // See that campaigns with only one run has the campaign_id as the id in Rogue.
        $this->assertDatabaseHas('campaigns', [
            'id' => 4,
            'internal_title' => 'Banned Books Club run 1',
            'cause' => 'Education',
            'secondary_causes' => 'Education',
            'impact_doc' => 'https://www.google.com/',
            'campaign_run_id' => 6167,
            'start_date' => '2015-08-27 00:00:00',
            'end_date' => null,
            'created_at' => '2016-01-14 20:31:36',
            'updated_at' => '2016-01-14 20:31:36',
        ]);

        // See that the campaigns with multiple runs take their campaign_run_id as id in Rogue except for last run.
        $this->assertDatabaseHas('campaigns', [
            'id' => 6170,
            'internal_title' => 'Mirror Messages run 1',
            'cause' => 'Mental Health',
            'secondary_causes' => 'Bullying,Mental Health',
            'impact_doc' => 'https://www.google.com/',
            'campaign_run_id' => 6170,
            'start_date' => '2015-08-27 00:00:00',
            'end_date' => null,
            'created_at' => '2016-01-14 20:31:36',
            'updated_at' => '2016-01-14 20:31:36',
        ]);

        $this->assertDatabaseHas('campaigns', [
            'id' => 6803,
            'internal_title' => 'Mirror Messages Run 2',
            'cause' => 'Mental Health',
            'secondary_causes' => 'Bullying,Mental Health',
            'impact_doc' => 'https://www.google.com/',
            'campaign_run_id' => 6803,
            'start_date' => '2016-03-31 00:00:00',
            'end_date' => '2016-04-30 00:00:00',
            'created_at' => '2016-03-31 16:20:32',
            'updated_at' => '2016-05-10 16:53:35',
        ]);

        $this->assertDatabaseHas('campaigns', [
            'id' => 7,
            'internal_title' => 'Mirror Messages run 3',
            'cause' => 'Mental Health',
            'secondary_causes' => 'Bullying,Bullying,Mental Health,Mental Health,Bullying,Bullying,Mental Health,Mental Health,Bullying,Bullying,Mental Health,Mental Health,Bullying,Bullying,Mental Health,Mental Health',
            'impact_doc' => 'https://www.google.com/',
            'campaign_run_id' => 6999,
            'start_date' => '2016-06-23 00:00:00',
            'end_date' => '2016-10-26 00:00:00',
            'created_at' => '2016-06-23 19:01:37',
            'updated_at' => '2016-10-27 17:50:42',
        ]);

        // See that the last campaign in the csv takes the campaign_id as the id in Rogue.
        $this->assertDatabaseHas('campaigns', [
            'id' => 8,
            'internal_title' => 'Custodian Care Run 1',
            'cause' => 'Education',
            'secondary_causes' => 'Education,Mental Health',
            'impact_doc' => 'https://www.google.com/',
            'campaign_run_id' => 5446,
            'start_date' => '2014-02-24 00:00:00',
            'end_date' => '2015-06-02 00:00:00',
            'created_at' => '2015-06-01 17:40:55',
            'updated_at' => '2016-01-25 23:14:02',
        ]);

        // Run the cammpaign again and make sure there are no duplicates
        $this->artisan('rogue:ashescampaignsimport', ['path' => 'tests/Console/example-legacy-campaigns.csv']);
        $all_campaigns = Campaign::all();
        $this->assertTrue($all_campaigns->count() == 10);
    }
}
