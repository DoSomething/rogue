<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $morphClass = 'photo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['signup_id', 'northstar_id', 'file_url', 'edited_file_url', 'caption', 'status', 'source', 'remote_addr'];

    /**
     * Returns a parent Post model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function post()
    {
        return $this->morphOne(Post::class, 'postable');
    }

    /**
     * Get all of the photo's reactions.
     */
    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactionable');
    }
}
