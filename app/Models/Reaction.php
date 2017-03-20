<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reaction extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to date.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'northstar_id', 'reactionable_id', 'reactionable_type'];

    /**
     * Each reaction has events.
     */
    public function events()
    {
        $this->morphMany('Rogue\Models\Event', 'eventable');
    }

    /**
     * Get all of the owning reactionable models.
     */
    public function reactionable()
    {
        return $this->morphTo();
    }
}
