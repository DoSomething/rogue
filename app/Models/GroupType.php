<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class GroupType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('name', 'asc');
        });
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
