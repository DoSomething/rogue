<?php

namespace Rogue\Models;

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
    protected $fillable = ['id', 'signup_id', 'campaign_id', 'northstar_id', 'url', 'caption', 'status', 'source', 'remote_addr'];

    /**
     * Attributes that can be queried when filtering.
     *
     * This array is manually maintained. It does not necessarily mean that
     * any of these are actual indexes on the database... but they should be!
     *
     * @var array
     */
    public static $indexes = ['id', 'signup_id', 'campaign_id', 'northstar_id', 'status'];

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
        return $this->tags()->pluck('tag_name');
    }

    /**
     * Get the tags associated with this post.
     */
    public function tagSlugs()
    {
        return $this->tags()->pluck('tag_slug');
    }

    /**
     * Get the URL for the media for this post.
     */
    public function getMediaUrl()
    {
        if ($this->url === 'default') {
            // @TODO: We should either return a placeholder, or `null`.
            return 'default';
        }

        // If Glide is enabled, provide the default image URL.
        if (config('features.glide')) {
            return url('images/' . $this->id);
        }

        // Ask the storage driver for the path to the image for this post.
        $path = Storage::url('uploads/reportback-items/edited_' . $this->id . '.jpeg');

        return url($path);
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
        return [
            'id' => $this->id,
            'signup_id' => $this->signup_id,
            'quantity' => $this->signup->quantity,
            'why_participated' => $this->signup->why_participated,
            'campaign_id' => (string) $this->campaign_id,
            'campaign_run_id' => (string) $this->signup->campaign_run_id,
            'northstar_id' => $this->northstar_id,
            'url' => $this->getMediaUrl(),
            'caption' => $this->caption,
            'status' => $this->status,
            'remote_addr' => $this->remote_addr,
            'source' => $this->source,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->toIso8601String() : null,
        ];
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
     * Get the total quantity for all posts under the signup that this post is associated with.
     *
     * @return int
     */
    public function getTotalQuantity()
    {
        $signup = $this->signup;

        // If the post has NULL quantity, it means that the quantity is still on the signup
        if (is_null($this->quantity)) {
            return $signup->getQuantity();
        }

        // Loop through all the posts and sum their quantities
        $posts = $signup->posts;
        $quantity = 0;

        foreach ($posts as $post) {
            $quantity += $post->quantity;
        }

        return $quantity;
    }
}
