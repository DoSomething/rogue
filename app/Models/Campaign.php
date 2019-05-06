<?php

namespace Rogue\Models;

use Rogue\Types\Cause;
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
    protected $fillable = ['internal_title', 'cause', 'impact_doc', 'start_date', 'end_date'];

    /**
     * Attributes that can be queried when filtering.
     *
     * This array is manually maintained. It does not necessarily mean that
     * any of these are actual indexes on the database... but they should be!
     *
     * @var array
     */
    public static $indexes = ['id'];

    /**
     * Valid campaign causes & their human-readable names.
     *
     * @var array
     */
    public static $causes = [
    ];

    /**
     * Get the signups associated with this campaign.
     */
    public function signup()
    {
        return $this->hasMany(Signup::class);
    }

    /**
     * A campaign has many actions.
     */
    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    /**
     * A campaign has many posts.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Should we accept new signups & posts for this campaign?
     *
     * @return bool
     */
    public function isOpen()
    {
        $hasStarted = $this->start_date < now();
        $hasEnded = $this->end_date && $this->end_date < now();

        return $hasStarted && ! $hasEnded;
    }

    /**
     * Get a human-friendly list of this campaign's causes.
     *
     * @return array
     */
    public function getCauseNames()
    {
        return array_values(array_intersect_key(Cause::labels(), array_flip($this->cause)));
    }

    /**
     * Accessor for parsing comma-separated causes into an array.
     *
     * @return array
     */
    public function getCauseAttribute()
    {
        return explode(',', $this->attributes['cause']);
    }

    /**
     * Mutator for storing an array of causes as a comma-separated string.
     *
     * @param string|Carbon $value
     */
    public function setCauseAttribute($value)
    {
        $this->attributes['cause'] = implode(',', $value);
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
     * Gets campaign by action_id.
     *
     * @param $actionId
     */
    public static function fromActionId($actionId)
    {
        $action = Action::findOrFail($actionId);

        return self::findOrFail($action->campaign_id);
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
