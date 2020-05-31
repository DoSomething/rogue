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
}
