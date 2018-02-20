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
        return '/campaigns/' . $this->campaignId;
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
            '@activeAcceptButton' => '.button.-outlined-button.-accepted.is-selected',
            '@unactiveAcceptButton' => 'button.-outlined-button.-accepted',
            '@activeRejectButton' => '.button.-outlined-button.-rejected.is-selected',
            '@activeTagButton' => '.tag.is-active',
            '@quantityBox' => '.quantity',
            '@newQuantityForm' => 'new-quantity',
        ];
    }
}
