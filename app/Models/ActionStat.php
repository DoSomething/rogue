<?php

namespace Rogue\Models;

use Rogue\Models\Traits\HasCursor;
use Illuminate\Database\Eloquent\Model;

class ActionStat extends Model
{
    use HasCursor;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['action_id', 'impact', 'location', 'school_id'];

    /**
     * Attributes that can be queried when filtering.
     *
     * This array is manually maintained. It does not necessarily mean that
     * any of these are actual indexes on the database... but they should be!
     *
     * @var array
     */
    public static $indexes = ['action_id', 'location', 'school_id'];

    /**
     * Attributes that can be sorted by.
     *
     * This array is manually maintained. It does not necessarily mean that
     * any of these are actual indexes on the database... but they should be!
     *
     * @var array
     */
    public static $sortable = ['id', 'impact'];
}
