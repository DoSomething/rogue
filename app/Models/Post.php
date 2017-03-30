<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'signup_id', 'northstar_id', 'postable_id', 'postable_type', 'status', 'source', 'remote_addr'];

    /**
     * Returns Post data
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function content()
    {
        return $this->morphTo('postable');
    }

    /**
     * Each post has events.
     */
    public function events()
    {
        return $this->morphMany(Event::class, 'eventable');
    }

    /**
     * Each post belongs to a signup.
     */
    public function signup()
    {
        return $this->belongsTo(Signup::class);
    }

    /**
     * Each post has one review.
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
