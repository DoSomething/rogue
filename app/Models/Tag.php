<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;
use Conner\Tagging\Taggable;

class Tag extends Model
{
    use Taggable;

    protected $table = 'tagging_tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tag_group_id', 'slug', 'name', 'suggest', 'count'];

    /**
     * Get all of the posts that are assigned this tag.
     */
    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }
}
