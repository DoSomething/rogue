<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;
use Conner\Tagging\Taggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use Taggable, SoftDeletes;

    protected $table = 'tagging_tagged';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['taggable_id', 'taggable_type', 'tag_name', 'tag_slug', 'admin_northstar_id'];

    /**
     * Get all of the posts that are assigned this tag.
     */
    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    /**
     * Each tag has events.
     */
    public function events()
    {
        return $this->morphMany(Event::class, 'eventable');
    }
}
