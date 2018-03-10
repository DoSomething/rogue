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
