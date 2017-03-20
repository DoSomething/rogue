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
    protected $fillable = ['id', 'signup_id', 'northstar_id', 'event_type', 'submission_type', 'quantity', 'quantity_pending', 'why_participated', 'caption', 'status', 'source', 'remote_addr', 'reason'];


    public function eventable()
    {
        return $this->morphTo();
    }
}
