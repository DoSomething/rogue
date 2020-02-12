<?php

namespace Tests\Console;

use Tests\TestCase;
use Rogue\Models\Campaign;

class StandardizeCausesCommandTest extends TestCase
{
    /** @test */
    public function it_should_standardize_causes()
    {
        // Create the campaigns with causes that we'll stadardize
        $campaign1 = factory(Campaign::class)->create(['cause' => ['Olympic Gold Medal', 'Soccer Games', 'Team USA']])->first();
        $campaign2 = factory(Campaign::class)->create(['cause' => ['Atlanta-Games', 'Centennial-Park']])->first();
        $campaign3 = factory(Campaign::class)->create(['cause' => ['snowboard-cross', 'short-track']])->first();

        // Run the 'rogue:standardizecauses' command
        $this->artisan('rogue:standardizecauses');

        // And see that the standardized causes are stored in the database
        $this->assertDatabaseHas('campaigns', [
            'cause' => 'olympic-gold-medal,soccer-games,team-usa',
        ]);

        $this->assertDatabaseHas('campaigns', [
            'cause' => 'atlanta-games,centennial-park',
        ]);

        $this->assertDatabaseHas('campaigns', [
            'cause' => 'snowboard-cross,short-track',
        ]);
    }
}
