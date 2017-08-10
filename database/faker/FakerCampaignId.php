<?php

use Faker\Provider\Base;

class FakerCampaignId extends Base
{
    /**
     * A selection of campaign IDs from Phoenix Thor.
     *
     * @var array
     */
    protected static $ids = [
        1144, // Teens For Jeans
        1508, // Thumb Wars
        7656, // Sincerely, Us
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
