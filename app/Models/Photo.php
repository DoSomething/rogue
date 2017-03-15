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
    protected $fillable = ['signup_id', 'northstar_id', 'file_url', 'edited_file_url', 'caption', 'status', 'source', 'remote_addr'];

    /**
     * Returns a parent Post model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function post()
    {
        return $this->morphOne('Rogue\Models\Post', 'postable');
    }

    /**
     * Get all of the photo's reactions.
     */
    public function reactions()
    {
        return $this->morphMany('Rogue\Models\Reaction', 'reactionable');
    }
}
