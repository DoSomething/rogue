<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['event_id', 'signup_id', 'northstar_id', 'admin_northstar_id', 'status', 'old_status', 'comment'];

    //@TODO relationships.
}
