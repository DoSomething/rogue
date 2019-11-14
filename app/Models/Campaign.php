<?php

namespace Rogue\Models;

use Rogue\Types\Cause;
use Rogue\Models\Traits\HasCursor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes, HasCursor;

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
     * Attributes that can be sorted by.
     *
     * This array is manually maintained. It does not necessarily mean that
     * any of these are actual indexes on the database... but they should be!
     *
     * @var array
     */
    public static $sortable = ['id', 'accepted_count', 'pending_count'];

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
     * Run a quick 'COUNT(*)' query to get the count of pending and accepted
     * posts for this campaign (so we can use this efficiently later).
     */
    public function refreshCounts()
    {
        $this->pending_count = Post::getPostCount($this, 'pending');
        $this->accepted_count = Post::getPostCount($this, 'accepted');
        $this->save();
    }

    /**
     * Scope a query to only include "open" campaigns.
     */
    public function scopeWhereOpen($query)
    {
        $today = now()->format('Y-m-d');

        return $query->whereDate('start_date', '<=', $today)
            ->where(function (Builder $query) use ($today) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>', $today);
            });
    }

    /**
     * Scope a query to only include "closed" campaigns.
     */
    public function scopeWhereClosed($query)
    {
        $today = now()->format('Y-m-d');

        return $query->whereDate('start_date', '>', $today)
            ->orWhere(function (Builder $query) use ($today) {
                $query->whereNotNull('end_date')
                    ->whereDate('end_date', '<', $today);
            });
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
        $cause = $this->attributes['cause'];

        return blank($cause) ? [] : explode(',', $cause);
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
