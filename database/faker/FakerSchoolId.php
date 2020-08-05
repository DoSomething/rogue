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
        $ids = file('database/faker/schools.txt');

        return trim($ids[array_rand($ids)]);
    }
}
