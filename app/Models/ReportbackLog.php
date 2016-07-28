<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;

class ReportbackLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'reportback_id', 'northstar_id', 'drupal_id', 'op', 'quantity', 'why_participated', 'files', 'num_files', 'remote_addr', 'reason'];
}
