<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class GroupType extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'filter_by_location' => 'boolean',
        // This field will eventually be deprecated by filter_by_location.
        'filter_by_state' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filter_by_location',
        // This field will eventually be deprecated by filter_by_location.
        'filter_by_state',
        'name',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('name', 'asc');
        });
    }

    /**
     * Get the groups associated with this group type.
     */
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    /**
     * Creates an array of grouptype labels, where the key is the id and the value is the grouptype name.
     *
     * @return array
     */
    public static function labels()
    {
        return self::all()->reduce(function ($result, $groupType) {
            $result[$groupType->id] = $groupType->name;

            return $result;
        }, []);
    }
}
