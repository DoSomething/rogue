<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;

class ReportbackItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['reportback_id', 'file_id', 'caption', 'status', 'reviewed', 'reviewer', 'review_source', 'source', 'remote_addr'];

    /**
     * A reportback item belongs to one reportback.
     */
    public function reportback()
    {
        return $this->belongsTo(Reportback::class);
    }
}
