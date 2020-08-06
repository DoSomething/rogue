<?php

use Faker\Provider\Base;

class FakerSchoolId extends Base
{
    /**
     * Return a random School ID.
     *
     * @return mixed
     */
    public function school_id()
    {
        // We use real school ID's so GraphQL queries that join on Schools don't break.
        $ids = file('database/faker/schools.txt');

        return trim($ids[array_rand($ids)]);
    }
}
