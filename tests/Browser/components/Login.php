<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class Login extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '.container__block.-narrow';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertVisible($this->selector());
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@login-button' => '.button',
        ];
    }

    /**
     * Login with NORTHSTAR_EMAIL and NORTHSTAR_PASSWORD.
     *
     * @param \Laravel\Dusk\Browser
     */
    public function login($browser)
    {
        $browser->click('@login-button')
                ->assertPathIs('/register')
                ->clickLink('Log In')
                ->type('username', env('NORTHSTAR_EMAIL'))
                ->type('password', env('NORTHSTAR_PASSWORD'))
                ->press('Log In');
    }
}
