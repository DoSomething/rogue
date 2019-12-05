<?php

use Faker\Provider\Base;

class FakerSchoolId extends Base
{
    /**
     * A selection of schools from GraphQL
     *
     * @var array
     */
    protected static $ids = [
        '3401166',
        '3401452',
        '3401451',
        '3401457',
        '3401450',
        '3401460',
        '4802532',
        '4811718',
        '4820400',
        '4825422',
    ];

    /**
     * Return a random School ID.
     *
     * @return mixed
     */
    public function school_id()
    {
        return static::randomElement(static::$ids);
    }
}
