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
    protected $fillable = ['event_id', 'signup_id'];

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
    public function postData()
    {
        return $this->morphTo('post');
    }

    /**
     * Each post belongs to an event.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Each post belongs to a signup.
     */
    public function signup()
    {
        return $this->belongsTo(Signup::class);
    }
}
