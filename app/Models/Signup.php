<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;

class Signup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'event_id', 'northstar_id', 'campaign_id', 'campaign_run_id', 'quantity', 'quantity_pending', 'why_participated', 'source', 'created_at', 'updated_at'];

    /**
     * Attributes that can be queried when filtering.
     *
     * This array is manually maintained. It does not necessarily mean that
     * any of these are actual indexes on the database... but they should be!
     *
     * @var array
     */
    public static $indexes = [
        'campaign_id', 'campaign_run_id', 'updated_at', 'northstar_id', 'id',
    ];

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
        // @TODO: include tags with posts
        return $this->hasMany(Post::class);
    }

    /**
     * Get the 'pending' posts associated with this signup.
     */
    public function pending()
    {
        return $this->hasMany(Post::class)->where('status', '=', 'pending')->with('tagged');
    }

    /**
     * Get the 'accepted' posts associated with this signup.
     */
    public function accepted()
    {
        return $this->hasMany(Post::class)->where('status', '=', 'accepted')->with('tagged');
    }

    /**
     * Get the 'rejected' posts associated with this signup.
     */
    public function rejected()
    {
        return $this->hasMany(Post::class)->where('status', '=', 'rejected')->with('tagged');
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
        return [
            'id' => $this->id,
            'northstar_id' => $this->northstar_id,
            'campaign_id' => $this->campaign_id,
            'campaign_run_id' => $this->campaign_run_id,
            'quantity' => $this->quantity,
            'why_participated' => $this->why_participated,
            'source' => $this->source,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
