<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;

class FailedLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'drupal_id', 'nid', 'quantity', 'why_participated', 'file_url', 'caption', 'source', 'op'];
}
