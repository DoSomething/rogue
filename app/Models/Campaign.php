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
    protected $dates = ['deleted_at', 'start_date', 'end_date'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','internal_title', 'cause', 'secondary_causes', 'campaign_run_id', 'impact_doc', 'start_date', 'end_date', 'created_at', 'updated_at'];

    /**
     * Get the signups associated with this campaign.
     */
    public function signup()
    {
        return $this->hasMany(Signup::class);
    }

    /**
     * Mutator for setting the start_date field.
     *
     * @param string|Carbon $value
     */
    public function setStartDateAttribute($value)
    {
        $this->setArbitraryDateString('start_date', $value);
    }

    /**
     * Mutator for setting the end_date field.
     *
     * @param string|Carbon $value
     */
    public function setEndDateAttribute($value)
    {
        $this->setArbitraryDateString('end_date', $value);
    }

    /**
     * Mutator to parse non-standard date strings into dates accepted by Laravel.
     *
     * @param string|Carbon $value
     */
    public function setArbitraryDateString($attribute, $value)
    {
        if (is_null($value)) {
            $this->attributes[$attribute] = null;
            return;
        }
        // Parse user-entered strings like '10/31/1990' or `October 31st 1990'.
        if (is_string($value)) {
            $value = strtotime($value);
        }
        $this->attributes[$attribute] = $this->fromDateTime($value);
    }
}
