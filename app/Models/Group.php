<?php

namespace Rogue\Models;

use Rogue\Models\Traits\HasCursor;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasCursor;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city',
        'external_id',
        'goal',
        'group_type_id',
        'name',
        'state',
    ];

    /**
     * Attributes that can be queried when filtering.
     *
     * This array is manually maintained. It does not necessarily mean that
     * any of these are actual indexes on the database... but they should be!
     *
     * @var array
     */
    public static $indexes = ['external_id', 'id', 'group_type_id', 'state'];

    /**
     * Attributes that we can sort by with the '?orderBy' query parameter.
     *
     * This array is manually maintained. It does not necessarily mean that
     * any of these are actual indexes on the database... but they should be!
     *
     * @var array
     */
    public static $sortable = ['name'];
}
