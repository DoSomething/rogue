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
     * Each signup belongs to an event.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the posts associated with this signup.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
