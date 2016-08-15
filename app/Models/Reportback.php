<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;

class Reportback extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'northstar_id', 'drupal_id', 'campaign_id', 'campaign_run_id', 'quantity', 'why_participated', 'num_participants', 'flagged', 'flagged_reason', 'promoted', 'promoted_reason'];

    /**
     * Get the reportback items associated with this reportback.
     */
    public function items()
    {
        return $this->hasMany(ReportbackItem::class);
    }

    /**
     * The reactions that belong to the reportback.
     */
    public function reactions()
    {
        return $this->belongsToMany('App\Reaction');
    }
}
