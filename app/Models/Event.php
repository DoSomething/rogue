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
    protected $fillable = ['id', 'northstar_id', 'event_type', 'submission_type', 'quantity', 'quantity_pending', 'why_participated', 'caption', 'status', 'source', 'remote_addr', 'reason'];

    /**
     * An event has one signup.
     */
    public function signup()
    {
        return $this->belongsTo(Signup::class);
    }

    /**
     * An event has one post.
     */
    public function post()
    {
        return $this->hasOne(Post::class);
    }
}
