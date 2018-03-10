<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;

class HomeTest extends DuskTestCase
{
    /** @test */
    public function testHomepage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new HomePage);

            $browser->assertSeeIn('@title', 'Rogue');
        });
    }
}
