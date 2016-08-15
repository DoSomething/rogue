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
    protected $fillable = ['id', 'northstar_id', 'drupal_id', 'file_id', 'taxonomy_id'];

    /**
     * The reportbacks that belont to the reaction.
     */
    public function reportbacks()
    {
        return $this->belongsToMany('App\Reportbacks');
    }
}
