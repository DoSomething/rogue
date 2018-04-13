<?php

namespace Rogue\Models;

use Carbon\Carbon;
use Rogue\Events\PostTagged;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Always load a user's own reaction,
     * if they're logged-in.
     */
    protected $with = ['reaction', 'tags'];

    /**
     * The relationship counts that should be eager loaded on every query.
     *
     * @var array
     */
    protected $withCount = ['reactions'];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['signup'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'signup_id', 'campaign_id', 'northstar_id', 'type', 'action', 'details', 'quantity', 'url', 'text', 'status', 'source', 'source_details', 'remote_addr'];

    /**
     * Attributes that can be queried when filtering.
     *
     * This array is manually maintained. It does not necessarily mean that
     * any of these are actual indexes on the database... but they should be!
     *
     * @var array
     */
    public static $indexes = ['id', 'signup_id', 'campaign_id', 'action', 'northstar_id', 'status'];

    /**
     * Each post has events.
     */
    public function events()
    {
        return $this->morphMany(Event::class, 'eventable');
    }

    /**
     * Each post belongs to a signup.
     */
    public function signup()
    {
        return $this->belongsTo(Signup::class);
    }

    /**
     * Each post has one review.
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Get the reactions associated with this post.
     */
    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    /**
     * Get the reactions associated with this post
     * for the given user ID.
     */
    public function reaction()
    {
        return $this->hasOne(Reaction::class)
            ->where('northstar_id', auth()->id());
    }

    /**
     * Get the tags associated with this post.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    /**
     * Get the tags associated with this post.
     */
    public function tagNames()
    {
        return $this->tags->pluck('tag_name');
    }

    /**
     * Get the tags associated with this post.
     */
    public function tagSlugs()
    {
        return $this->tags->pluck('tag_slug');
    }

    /**
     * Get the URL for the media for this post.
     */
    public function getMediaUrl()
    {
        if ($this->url === null) {
            return null;
        }

        // If Glide is enabled, provide the default image URL.
        if (config('features.glide')) {
            return url('images/' . $this->id);
        }

        // Ask the storage driver for the path to the image for this post.
        $path = Storage::url('uploads/reportback-items/edited_' . $this->id . '.jpeg');

        return url($path) . '?time='. Carbon::now()->timestamp;
    }

    /**
     * Return the filesystem path for this post.
     *
     * @return string|null
     */
    public function getMediaPath()
    {
        $path = parse_url($this->url, PHP_URL_PATH);

        // We store local URLs prefixed with `/storage` when using the "public" adapter.
        if (config('filesystems.default') == 'public') {
            return str_replace('/storage/', '', $path);
        }

        // For S3, we store a full AWS URL, sometimes prefixed by bucket directory.
        if (config('filesystems.default') == 's3') {
            return str_replace('/'. config('filesystems.disks.s3.bucket') . '/', '', $path);
        }

        return null;
    }

    /**
     * Get the reactions associated with this post.
     */
    public function siblings()
    {
        return $this->hasMany(self::class, 'signup_id', 'signup_id');
    }

    /**
     * Transform the post model for Blink.
     *
     * @return array
     */
    public function toBlinkPayload()
    {
        // Blink expects quantity to be a number.
        $quantity = $this->quantity === null ? 0 : $this->quantity;

        return [
            'id' => $this->id,
            'signup_id' => $this->signup_id,
            'quantity' => $quantity,
            'why_participated' => $this->signup->why_participated,
            'campaign_id' => (string) $this->campaign_id,
            'campaign_run_id' => (string) $this->signup->campaign_run_id,
            'northstar_id' => $this->northstar_id,
            'type' => $this->type,
            'action' => $this->action,
            'url' => $this->getMediaUrl(),
            'caption' => $this->text,
            'text' => $this->text,
            'status' => $this->status,
            'remote_addr' => $this->remote_addr,
            'source' => $this->source,
            'details' => $this->details,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->toIso8601String() : null,
        ];
    }

    /**
     * Transform the post model for Quasar.
     *
     * @return array
     */
    public function toQuasarPayload()
    {
        return [
            'id' => $this->id,
            'signup_id' => $this->signup_id,
            'campaign_id' => $this->campaign_id,
            'campaign_run_id' => $this->signup->campaign_run_id,
            'northstar_id' => $this->northstar_id,
            'type' => $this->type,
            'action' => $this->action,
            'quantity' => $this->getQuantity(),
            'why_participated' => $this->signup->why_participated,
            // Add cache-busting query string to urls to make sure we get the
            // most recent version of the image.
            // @NOTE - Remove if we get rid of rotation.
            'media' => [
                'url' => $this->getMediaUrl(),
                'caption' => $this->text,
                'text' => $this->text,
            ],
            'tags' => $this->tagSlugs(),
            'status' => $this->status,
            'source' => $this->source,
            'signup_source' => $this->signup->source,
            'remote_addr' => $this->remote_addr,
            'details' => $this->details,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'signup_created_at' => $this->signup->created_at->toIso8601String(),
            'signup_updated_at' => $this->signup->updated_at->toIso8601String(),
            'meta' => [
                'message_source' => 'rogue',
                'type' => 'post',
            ],
        ];
    }

    /**
     * If we are storing quanity on the post, return that!
     * If the quantity is not on the post, return the quantity from the signup.
     *
     * @return int
     */
    public function getQuantity()
    {
        if (! config('features.v3QuantitySupport')) {
            return $this->signup->getQuantity();
        }

        return $this->quantity;
    }

    /**
     * Apply the given tag to this post.
     */
    public function tag($tagName)
    {
        $tag = Tag::firstOrCreate(['tag_name' => $tagName], ['tag_slug' => str_slug($tagName, '-')]);

        $this->tags()->attach($tag);

        // Update timestamps on the Post when adding a tag
        $this->touch();

        event(new PostTagged($this, $tag));

        return $this;
    }

    /**
     * Remove the given tag from this post.
     */
    public function untag($tagName)
    {
        $tag = Tag::where('tag_name', $tagName)->first();

        $this->tags()->detach($tag);

        // Update timestamps on the Post when removing a tag
        $this->touch();

        // @TODO: create an event here when we refactor events system.

        return $this;
    }

    /**
     * Returns posts without specific tag(s).
     */
    public function scopeWithoutTag($query, $tagSlug)
    {
        return $query->whereDoesntHave('tags', function ($query) use ($tagSlug) {
            multipleValueQuery($query, $tagSlug, 'tag_slug');
        });
    }

    /**
     * Returns posts with specific tag(s).
     */
    public function scopeWithTag($query, $tagSlug)
    {
        return $query->whereHas('tags', function ($query) use ($tagSlug) {
            multipleValueQuery($query, $tagSlug, 'tag_slug');
        });
    }

    /**
     * Get the number of reactions a post has.
     *
     * @param int $postId
     * @return int
     */
    public function getTotalReactions($postId)
    {
        return Reaction::where('post_id', $postId)->count();
    }

    /**
     * Scope a query to only return posts if a user is an admin, staff, or is owner of post.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereVisible($query)
    {
        if (! is_staff_user()) {
            return $query->where('status', 'accepted')
                         ->orWhere('northstar_id', auth()->id());
        }
    }
}
