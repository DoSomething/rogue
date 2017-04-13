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
        'campaign_id', 'campaign_run_id',
    ];

    /**
     * Each signup has events.
     */
    public function events()
    {
        return $this->morphMany(Event::class, 'eventable');
    }

    /**
     * Get the posts associated with this signup.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the 'pending' posts associated with this signup.
     */
    public function pending()
    {
        return $this->hasMany(Post::class)->where('status', '=', 'pending');
    }

    /**
     * Get the 'accepted' posts associated with this signup.
     */
    public function accepted()
    {
        return $this->hasMany(Post::class)->where('status', '=', 'accepted');
    }

    /**
     * Get the 'rejected' posts associated with this signup.
     */
    public function rejected()
    {
        return $this->hasMany(Post::class)->where('status', '=', 'accepted');
    }

    /**
     * Scope a query to only include signups for a particular campaign
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCampaign($query, $id)
    {
        return $query->where('campaign_id', $id);
    }

    /**
     * Scope a query to only include signups for a particular campaign
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPosts($query, $status = '')
    {
        if ($status === 'pending') {
            return $query->with('pending');
        } else if ($status === 'accepted') {
            return $query->with('accepted');
        } else if ($status === 'rejected') {
            return $query->with('rejected');
        } else {
            return $query->with('posts');
        }
    }

    public function scopeIncludeStatusCounts($query)
    {
        return $query->withCount(['accepted', 'pending', 'rejected']);
    }
}
