<?php

namespace Tests\Console;

use Rogue\Models\Campaign;
use Tests\TestCase;

class StandardizeCausesCommandTest extends TestCase
{
    /** @test */
    public function it_should_standardize_causes()
    {
        // Create the campaigns with causes that we'll stadardize
        $campaign1 = factory(Campaign::class)->create([
            'cause' => ['Olympic Gold Medal', 'Soccer Games', 'Team USA'],
        ]);
        $campaign2 = factory(Campaign::class)->create([
            'cause' => ['Atlanta-Games', 'Centennial-Park'],
        ]);
        $campaign3 = factory(Campaign::class)->create([
            'cause' => ['snowboard-cross', 'short-track'],
        ]);
        $campaign4 = factory(Campaign::class)->create([
            'cause' => [' Equal Pay  ', 'Corner Kick '],
        ]);

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

        $this->assertDatabaseHas('campaigns', [
            'cause' => 'equal-pay,corner-kick',
        ]);
    }
}
