<?php

namespace Rogue\Models;

use Rogue\Services\GraphQL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Signup extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'campaign_id' => 'string',
    ];

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
    protected $fillable = ['id', 'northstar_id', 'campaign_id', 'quantity', 'quantity_pending', 'why_participated', 'source', 'source_details', 'details', 'created_at', 'updated_at'];

    /**
     * Attributes that can be queried when filtering.
     *
     * This array is manually maintained. It does not necessarily mean that
     * any of these are actual indexes on the database... but they should be!
     *
     * @var array
     */
    public static $indexes = [
        'campaign_id', 'updated_at', 'northstar_id', 'id', 'quantity', 'source',
    ];

    /**
     * Each signup belongs to a campaign.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Each signup has events.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function events()
    {
        return $this->morphMany(Event::class, 'eventable');
    }

    /**
     * Get the posts associated with this signup.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class)->with('tags');
    }

    /**
     * Get the visible posts associated with this signup.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function visiblePosts()
    {
        $query = $this->hasMany(Post::class);

        if (! is_staff_user()) {
            $query->where(function ($query) {
                $query->where('status', 'accepted')
                    ->orWhere('northstar_id', auth()->id());
            });
        }

        return $query;
    }

    /**
     * Get the 'pending' posts associated with this signup.
     */
    public function pending()
    {
        return $this->hasMany(Post::class)->where('status', '=', 'pending')->with('tags');
    }

    /**
     * Get the 'accepted' posts associated with this signup.
     */
    public function accepted()
    {
        return $this->hasMany(Post::class)->where('status', '=', 'accepted')->with('tags');
    }

    /**
     * Get the 'rejected' posts associated with this signup.
     */
    public function rejected()
    {
        return $this->hasMany(Post::class)->where('status', '=', 'rejected')->with('tags');
    }

    /**
     * Scope a query to only include signups for a particular campaign
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCampaign($query, $ids)
    {
        return $query->wherein('campaign_id', $ids);
    }

    /**
     * Scope a query to include a count of post statuses for a signup.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIncludePostStatusCounts($query)
    {
        return $query->withCount(['accepted', 'pending', 'rejected']);
    }

    /**
     * Transform the signup model for Blink.
     *
     * @return array
     */
    public function toBlinkPayload()
    {
        // Blink expects quantity to be a number.
        $quantity = $this->quantity === null ? 0 : $this->quantity;

        // Bypass Campaign->cause accessor method so the value isn't converted to an array
        // which Customer.io does not support.
        $campaign_cause = optional($this->campaign)->getAttributes()['cause'];

        // Fetch Campaign Website information via GraphQL.
        $campaignWebsite = app(GraphQL::class)->getCampaignWebsiteByCampaignId($this->campaign_id);

        return [
            'id' => $this->id,
            'northstar_id' => $this->northstar_id,
            'campaign_id' => (string) $this->campaign_id,
            'campaign_run_id' => (string) $this->campaign_run_id,
            'campaign_title' => $campaignWebsite['title'],
            'campaign_slug' => $campaignWebsite['slug'],
            'campaign_cause' => $campaign_cause,
            'quantity' => $quantity,
            'why_participated' => $this->why_participated,
            'source' => $this->source,
            'source_details' => $this->source_details,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }

    /**
     * Transform the signup model for Quasar.
     *
     * @return array
     */
    public function toQuasarPayload()
    {
        return [
            'signup_id' => $this->id,
            'northstar_id' => $this->northstar_id,
            'campaign_id' => $this->campaign_id,
            'campaign_run_id' => $this->campaign_run_id,
            'quantity' => $this->getQuantity(),
            'why_participated' => $this->why_participated,
            'signup_source' => $this->source,
            'details' => $this->details,
            'source_details' => $this->source_details,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'meta' => [
                'message_source' => 'rogue',
                'type' => 'signup',
            ],
        ];
    }

    /**
     * Get the total quantity for this signup's posts.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->posts()->sum('quantity');
    }

    /**
     * Get the quantity total associated with approved posts under this signup
     *
     * @return int
     */
    public function getAcceptedQuantity()
    {
        $accepted_posts = $this->posts->where('status', 'accepted');

        return $accepted_posts->sum('quantity');
    }

    /**
     * Scope a query to only return signups if a user is an admin, staff, or is owner of signup and by type (optional)
     *
     * @param array $types
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithVisiblePosts($query, $types = null)
    {
        return $query->with(['visiblePosts' => function ($query) use ($types) {
            if ($types) {
                $query->whereIn('type', $types);
            }
        }]);
    }
}
