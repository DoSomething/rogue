<?php

namespace Rogue\Models;

class Activity
{
    /**
     * Attributes that can be queried when filtering.
     *
     * This array is manually maintained. It does not necessarily mean that
     * any of these are actual indexes on the database... but they should be!
     *
     * @var array
     */
    public static $indexes = [
        'campaigns', 'camapign_runs', 'count', 'page',
    ];
}
