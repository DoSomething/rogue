<?php

use Faker\Generator;
use League\Csv\Reader;
use Faker\Provider\Base;

class FakerSchoolId extends Base
{
    /**
     * A list of schools from our Schools DB.
     *
     * @var array
     */
    protected $schools = [];

    public function __construct(Generator $generator)
    {
        parent::__construct($generator);

        // We use real school ID's so GraphQL queries that join on Schools don't break.
        $reader = Reader::createFromPath('database/faker/schools.csv', 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();

        foreach ($records as $record) {
            array_push($this->schools, $record);
        }
    }

    /**
     * Return a random School.
     *
     * @return object
     */
    public function school()
    {
        $randomKey = array_rand($this->schools);

        return (object) $this->schools[$randomKey];
    }

    /**
     * Return a random School ID.
     *
     * @return string
     */
    public function school_id()
    {
        return $this->school()->school_id;
    }
}
