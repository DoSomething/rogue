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
    protected $fillable = ['event_id', 'signup_id', 'northstar_id'];

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

    /**
     * Each post has many reactions.
     */
    public function reactions()
    {
        return $this->morphToMany(Reaction::class, 'postable');
    }

    /**
    * Query for total reactions for a post.
     *
     * @param int $postableId
     * @param int $postableType
     * @return int total count
     */
    public static function withReactionCount($postableId, $postableType)
    {
        return self::withCount(['reactions' => function ($query) use ($postableId, $postableType) {
            $query->where(['postable_id' => $postableId, 'postable_type' => $postableType]);
        }])->get();
    }

    /**
     * Each post has one review.
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
