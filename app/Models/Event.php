<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['eventable_id', 'eventable_type', 'content'];

    public function eventable()
    {
        return $this->morphTo();
    }
}
