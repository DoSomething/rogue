<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;
use Rogue\Models\Traits\HasCursor;

class Club extends Model
{
    use HasCursor;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'leader_id',
        'school_id',
        'location',
        'city',
    ];

    /**
     * Attributes that can be queried when filtering.
     *
     * This array is manually maintained. It does not necessarily mean that
     * any of these are actual indexes on the database... but they should be!
     *
     * @var array
     */
    public static $indexes = ['id'];
}
