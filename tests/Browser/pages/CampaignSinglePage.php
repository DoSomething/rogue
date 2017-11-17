<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class CampaignSinglePage extends Page
{
    protected $campaignId;

    public function __construct($campaignId)
    {
        $this->campaignId = $campaignId;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/campaigns' . $campaignId;
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        //
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@filter_value' => 'filter_value',
            '@individualPost' => '.post container__row',
            '@activeAcceptButton' => '.button -outlined-button -accepted is-selected',
            '@postsTitle' => '.heading -emphasized',
        ];
    }
}
// $browser->click('.login-page .container div > button');
