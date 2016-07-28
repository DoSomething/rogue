<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;

class Kudos extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'northstar_id', 'drupal_id', 'file_id', 'taxonomy_id'];
}
