<?php

use Faker\Provider\Base;

class FakerCampaignId extends Base
{
    /**
     * A selection of campaign IDs from Phoenix Staging.
     *
     * @var array
     */
    protected static $ids = [
        1173, // Thumb Wars
        1631, // Space Jam
        1283, // Patient Playbooks
        8017, // Grab the Mic
        2072, // Scrub-A-Dub Dog
        7402, // Shred Hate
        2091, // Pineapple Push-Ups
        2710, // #SuperStressFace
        1822, // Re-poppin' Bottles
        3590, // Shower Songs
    ];

    /**
     * Return a random campaign ID.
     *
     * @return mixed
     */
    public function campaign_id()
    {
        return static::randomElement(static::$ids);
    }
}
