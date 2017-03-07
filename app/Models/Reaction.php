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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'northstar_id', 'postable_id', 'postable_type'];

    /**
     * Each reaction belongs to a post.
     */
    public function post()
    {
        return $this->morphedByMany(Post::class, 'postable');
    }
}
