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
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['post'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'northstar_id', 'post_id'];

    /**
     * Each reaction has events.
     */
    public function events()
    {
        return $this->morphMany(Event::class, 'eventable');
    }

    /**
     * Each reaction belongs to a post.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
