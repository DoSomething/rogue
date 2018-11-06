<?php

namespace Rogue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['internal_title', 'start_date', 'end_date'];

    /**
     * Get the signups associated with this campaign.
     */
    public function signup()
    {
        return $this->hasMany(Signup::class);
    }

    /**
     * Get the display start date for the campaign.
     */
    public function displayStartDate()
    {
        return date("m/d/Y", strtotime($this->start_date));
    }

    /**
     * Get the display end date for the campaign.
     */
    public function displayEndDate($end_date_placeholder)
    {
        return $this->end_date ? date("m/d/Y", strtotime($this->end_date)) : $end_date_placeholder;
    }
}
