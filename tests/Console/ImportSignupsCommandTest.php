<?php

namespace Tests\Console;

use Carbon\Carbon;
use Tests\TestCase;
use Rogue\Models\Signup;

class ImportSignupsCommandTest extends TestCase
{
    public function testImportingSignupsWithNoDuplicates()
    {
        // Timestamp right before running the script
        $start_time = $this->mockTime(Carbon::now());

        // Run the signup import command
        $this->artisan('rogue:signupimport', ['path' => 'tests/Console/example-signups.csv']);

        // And see that we stored the two unique signups
        $this->assertDatabaseHas('signups', [
            'northstar_id' => '56e83a07469c64d8578b5ed4',
            'campaign_id' => '362',
            'source' => 'phoenix-next',
            'created_at' => '2014-03-13 21:39:01',
        ]);

        $this->assertDatabaseHas('signups', [
            'northstar_id' => '5589c9bb469c6475138b81f0',
            'campaign_id' => '1144',
            'source' => 'phoenix-next',
            'created_at' => '2013-11-06 23:32:03',
        ]);

        // Make sure there are no duplicates
        $all_signups = Signup::all();
        $this->assertTrue($all_signups->count() == 2);

        // Make sure the 'updated_at' timestamps are not backdated
        foreach ($all_signups as $signup) {
            $this->assertTrue($signup->updated_at->gte($start_time));
        }
    }
}
