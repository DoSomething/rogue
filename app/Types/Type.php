<?php

namespace Rogue\Types;

use MyCLabs\Enum\Enum;

abstract class Type extends Enum
{
    /**
     * Return all string values.
     *
     * @return array
     */
    public static function all()
    {
        return array_values(self::toArray());
    }
}
