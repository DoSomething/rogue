<?php

use Faker\Provider\Base;

class FakerContentfulCampaignId extends Base
{
    /**
     * A selection of test campaign IDs from Contentful.
     *
     * @var array
     */
    protected static $ids = [
        '6LQzMvDNQcYQYwso8qSkQ8', // [Test] Teens for Jeans 2017
        '4w1fBp7W5WAowuu0wKwAac', // [Legacy Test] Mirror Messages 2017-10
        '5bUfbCp98sicAKSoscqUUO', // [LegacyTest] Thumb Wars 2017
    ];

    /**
     * Return a random contentful campaign ID.
     *
     * @return mixed
     */
    public function contentful_id()
    {
        return static::randomElement(static::$ids);
    }
}
