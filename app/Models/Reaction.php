<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'northstar_id', 'drupal_id', 'taxonomy_id'];

    /**
     * The reportback items that belongs to the reaction.
     */
    public function reportbackItems()
    {
        return $this->belongsToMany('\Rogue\Models\ReportbackItem');
    }
}
