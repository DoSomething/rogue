<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reaction extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to date.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'northstar_id', 'reportback_item_id'];

    /**
     * The reportback items that belongs to the reaction.
     */
    public function reportbackItems()
    {
        return $this->belongsTo('\Rogue\Models\ReportbackItem');
    }
}
