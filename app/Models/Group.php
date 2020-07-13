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
        'goal',
        'group_type_id',
        'name',
        'school_id',
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
    public static $indexes = [
        'group_type_id',
        'id',
        'school_id',
        'state',
    ];

    /**
     * Attributes that we can sort by with the '?orderBy' query parameter.
     *
     * This array is manually maintained. It does not necessarily mean that
     * any of these are actual indexes on the database... but they should be!
     *
     * @var array
     */
    public static $sortable = ['name'];

    /**
     * Get the group type associated with this group.
     */
    public function group_type()
    {
        return $this->belongsTo(GroupType::class);
    }

    /**
     * Get the posts associated with this group.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the signups associated with this group.
     */
    public function signups()
    {
        return $this->hasMany(Signup::class);
    }

    /**
     * Returns Customer.io payload attributes for given group, if exists.
     *
     * @param Group $group
     * @return array
     */
    public static function toBlinkPayload(?self $group)
    {
        return [
            'group_id' => $group ? $group->id : null,
            'group_name' => $group ? $group->name : null,
            'group_type_id' => $group ? $group->group_type->id : null,
            'group_type_name' => $group ? $group->group_type->name : null,
        ];
    }
}
