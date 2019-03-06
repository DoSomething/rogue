<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Action extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'reportback' => 'boolean',
        'civic_action' => 'boolean',
        'scholarship_entry' => 'boolean',
        'anonymous' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'campaign_id', 'post_type', 'callpower_campaign_id', 'reportback', 'civic_action', 'scholarship_entry', 'anonymous', 'noun', 'verb'];

    /**
     * Attributes that can be queried when filtering.
     *
     * This array is manually maintained. It does not necessarily mean that
     * any of these are actual indexes on the database... but they should be!
     *
     * @var array
     */
    public static $indexes = ['id'];

    /**
     * Each action belongs to a campaign.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * An action has many posts.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
