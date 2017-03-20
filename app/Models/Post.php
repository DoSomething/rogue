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
    protected $fillable = ['event_id', 'signup_id', 'northstar_id', 'status', 'source', 'remote_addr'];

    protected $primaryKey = ['event_id'];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

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
        $this->morphMany('Rogue\Models\Event', 'eventable');
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
