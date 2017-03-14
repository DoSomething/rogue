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

    /**
     * Query for total reactions for a photo.
     *
     * @param int $reactionableId
     * @return int total count
     */
    public static function withReactionCount($reactionableId)
    {

        // $posts = Post::withCount(['votes', 'comments' => function ($query) {
        //     $query->where('content', 'like', 'foo%');
        // }])->get();
        return self::withCount(['reactions' => function ($query) use ($reactionableId) {
            $query->where(['id' => $reactionableId]);
        }])->get();
    }
}
