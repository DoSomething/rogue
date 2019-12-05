<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;

class ActionStat extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'accepted_quantity',
         'action_id',
         'school_id',
    ];

    /**
     * Attributes that can be queried when filtering.
     *
     * This array is manually maintained. It does not necessarily mean that
     * any of these are actual indexes on the database... but they should be!
     *
     * @var array
     */
    public static $indexes = ['action_id', 'school_id'];

    /**
     * Attributes that can be sorted by.
     *
     * This array is manually maintained. It does not necessarily mean that
     * any of these are actual indexes on the database... but they should be!
     *
     * @var array
     */
    public static $sortable = ['id', 'accepted_quantity'];
}
