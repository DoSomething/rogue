<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['event_id', 'signup_id', 'northstar_id', 'file_url', 'edited_file_url', 'caption', 'status', 'source', 'remote_addr'];

    /**
     * Each photo belongs to an event.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Each photo belongs to a signup.
     */
    public function signup()
    {
        return $this->belongsTo(Signup::class);
    }
}
