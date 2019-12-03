<?php

namespace Rogue\Models;

use Hashids\Hashids;
use Rogue\Services\GraphQL;
use Rogue\Events\PostTagged;
use Rogue\Models\Traits\HasCursor;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Post extends Model
{
    use SoftDeletes, HasCursor;

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
    protected $with = ['reaction', 'tags', 'actionModel'];

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
    protected $fillable = ['id', 'signup_id', 'campaign_id', 'northstar_id', 'type', 'action', 'action_id', 'details', 'quantity', 'url', 'text', 'status', 'source', 'source_details', 'location', 'postal_code', 'school_id'];

    /**
     * Attributes that can be queried when filtering.
     *
     * This array is manually maintained. It does not necessarily mean that
     * any of these are actual indexes on the database... but they should be!
     *
     * @var array
     */
    public static $indexes = ['id', 'signup_id', 'campaign_id', 'type', 'action', 'action_id', 'northstar_id', 'status', 'created_at', 'source', 'location'];

    /**
     * Attributes that we can sort by with the '?orderBy' query parameter.
     *
     * This array is manually maintained. It does not necessarily mean that
     * any of these are actual indexes on the database... but they should be!
     *
     * @var array
     */
    public static $sortable = ['created_at'];

    /**
     * The tags prefixed with 'good' that will send a post to Slack.
     *
     * @var array
     */
    public $goodTags = ['good-for-storytelling', 'good-submission', 'good-for-sponsor', 'good-quote', 'good-for-brand'];

    /**
     * Get a post by its unique hash.
     *
     * @param string $hash
     * @return Post
     */
    public static function findByHashOrFail($hash)
    {
        $result = app(Hashids::class)->decode($hash);

        if (empty($result)) {
            throw new ModelNotFoundException;
        }

        return self::findOrFail($result[0]);
    }

    /**
     * Each post belongs to an action.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

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
     * Each post belongs to an action.
     */
    public function actionModel()
    {
        return $this->belongsTo(Action::class, 'action_id');
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
     * Get the location as a human-readable name.
     *
     * @return string
     */
    public function getLocationNameAttribute()
    {
        $code = $this->attributes['location'];
        if (! $code) {
            return null;
        }

        return Cache::rememberForever('region-'.$code, function () use ($code) {
            $isoCodes = new \Sokil\IsoCodes\IsoCodesFactory();
            $subDivisions = $isoCodes->getSubdivisions();
            $region = $subDivisions->getByCode($code);

            return $region ? $region->getLocalName() : null;
        });
    }

    /**
     * Create a hard-to-guess "hash" ID.
     *
     * @return string
     */
    public function getHashAttribute()
    {
        return app(Hashids::class)->encode($this->id);
    }

    /**
     * Get the URL for the media for this post.
     */
    public function getMediaUrl()
    {
        if ($this->url === null) {
            return null;
        }

        return url('images/' . $this->hash);
    }

    /**
     * Get the URL for the media for this post.
     */
    public function getOriginalUrl()
    {
        if ($this->url === null) {
            return null;
        }

        return url('originals/' . $this->id);
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
     * Get the siblings associated with this post.
     */
    public function siblings()
    {
        return $this->hasMany(self::class, 'signup_id', 'signup_id')->take(5);
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

        // Bypass Campaign->cause accessor method so the value isn't converted to an array
        // which Customer.io does not support.
        $campaign_cause = optional($this->signup->campaign)->getAttributes()['cause'];

        // Fetch Campaign Website information via GraphQL.
        $campaignWebsite = app(GraphQL::class)->getCampaignWebsiteByCampaignId($this->campaign_id);

        // Fetch School information via GraphQL.
        if ($this->school_id) {
            $school = app(GraphQL::class)->getSchoolById($this->school_id);
        }

        // The associated Action for this post.
        $action = $this->actionModel;

        return [
            'id' => $this->id,
            'signup_id' => $this->signup_id,
            'quantity' => $quantity,
            'why_participated' => $this->signup->why_participated,
            'campaign_id' => (string) $this->campaign_id,
            'campaign_run_id' => (string) $this->signup->campaign_run_id,
            'campaign_title' => $campaignWebsite['title'],
            'campaign_slug' => $campaignWebsite['slug'],
            'campaign_cause' => $campaign_cause,
            'northstar_id' => $this->northstar_id,
            'type' => $this->type,
            'action' => $this->getActionName(),
            'action_id' => $this->action_id,
            'action_type' => $action['action_type'],
            'scholarship_entry' => $action['scholarship_entry'],
            'civic_action' => $action['civic_action'],
            'quiz' => $action['quiz'],
            'online' => $action['online'],
            'time_commitment' => $action['time_commitment'],
            'url' => $this->getMediaUrl(),
            'caption' => $this->text,
            'text' => $this->text,
            'status' => $this->status,
            'remote_addr' => '0.0.0.0',
            'source' => $this->source,
            'source_details' => $this->source_details,
            'details' => $this->details,
            'school_id' => $this->school_id,
            'school_name' => isset($school) ? $school['name'] : null,
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
            'action' => $this->getActionName(),
            'action_id' => $this->action_id,
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
            'source_details' => $this->source_details,
            'signup_source' => $this->signup->source,
            'remote_addr' => '0.0.0.0',
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
     * Get the post's action name.
     * @TODO: This function should be deleted once we delete the action column from the posts table.
     */
    public function getActionName()
    {
        return $this->actionModel ? $this->actionModel['name'] : $this->action;
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
     * @param $tag Tag to tag post with
     */
    public function tag($tag)
    {
        // If a tag slug is sent in, change to the tag name.
        if (strpos($tag, '-')) {
            $tagArray = explode('-', $tag);
            $tag = implode(' ', $tagArray);
        }

        // Capitalize each word in the tag.
        $tagName = ucwords($tag);

        // Only tag if the tag doesn't exist on the post yet.
        // Otherwise, an integrity constraint violation / duplicate entry error will be thrown.
        if (! $this->tagNames()->contains($tagName)) {
            $tag = Tag::firstOrCreate(['tag_name' => $tagName], ['tag_slug' => str_slug($tagName, '-')]);

            $this->tags()->attach($tag);

            // Update timestamps on the Post when adding a tag
            $this->touch();

            event(new PostTagged($this, $tag));
        }

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
     * Returns posts that are reviewable.
     */
    public function scopeWhereReviewable($query)
    {
        return $query->whereIn('type', ['photo', 'text']);
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
     * Scope a query to only return posts if a user is an admin, staff, or is owner of post and the post's action is not anonymous.
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

    /**
     * Scope a query to only return anonymous posts if a user is an admin, staff, or is owner of post.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutAnonymousPosts($query)
    {
        if (! is_staff_user()) {
            return $query->whereDoesntHave('actionModel', function ($query) {
                $query->where('anonymous', true);
            })
                ->orWhere('northstar_id', auth()->id());
        }
    }

    /**
     * Scope a query to only return posts tagged as "Hide In Gallery" if a user is an admin, staff, or is owner of post.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithHiddenPosts($query)
    {
        if (! is_staff_user()) {
            return $query->whereDoesntHave('tags', function ($query) {
                $query->where('tag_slug', 'hide-in-gallery');
            })
                ->orWhere('northstar_id', auth()->id());
        }
    }

    /**
     * Return whether or not a post already has a "good" tag.
     *
     * @return bool
     */
    public function hasGoodTag()
    {
        foreach ($this->tagSlugs() as $tagslug) {
            if (in_array($tagslug, $this->goodTags)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the number of posts for the given campaign and status.
     *
     * @return int
     */
    public static function getPostCount(Campaign $campaign, string $status)
    {
        return (new self)->newModelQuery()
            ->where('campaign_id', $campaign->id)
            ->where('status', $status)
            ->whereReviewable()
            ->count();
    }

    /**
     * Get the sum quantity of accepted posts with given action and school ID.
     *
     * @return int
     */
    public static function getAcceptedQuantitySum(Action $action, string $schoolId)
    {
        return (new self)->newModelQuery()
            ->where('action_id', $action->id)
            ->where('school_id', $schoolId)
            ->where('status', 'accepted')
            ->sum('quantity');
    }
}
