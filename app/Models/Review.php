<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['signup_id', 'northstar_id', 'admin_northstar_id', 'status', 'old_status', 'comment', 'post_id'];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['post', 'signup'];

    /**
     * Each review has events.
     */
    public function events()
    {
        return $this->morphMany(Event::class, 'eventable');
    }

    /**
     * Each review belongs to a post.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function signup()
    {
        return $this->post->signup();
    }
}
