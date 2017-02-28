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

    /**
     * An event has one signup.
     */
    public function signup()
    {
        return $this->hasOne(Signup::class);
    }

    /**
     * An event has one post.
     */
    public function post()
    {
        return $this->hasOne(Post::class);
    }

    /**
     * An event has one reportback.
     */
    public function reportback()
    {
        return $this->hasOne(Reportback::class);
    }
}
