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
        $this->morphMany('Rogue\Models\Event', 'eventable');
    }

    /**
     * Get the posts associated with this signup.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
