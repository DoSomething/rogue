<?php

use Carbon\Carbon;
use Illuminate\Support\HtmlString;

/**
 * Create a script tag to set a global variable.
 *
 * @param $json
 * @param string $store
 * @return HtmlString
 */
function scriptify($json = [], $store = 'STATE')
{
    return new HtmlString('<script type="text/javascript">window.'.$store.' = '.json_encode($json).'</script>');
}

/**
 * Check if the current route has any middleware attached.
 *
 * @param  null|string  $middleware
 * @return bool
 */
function has_middleware($middleware = null)
{
    $currentRoute = app('router')->getCurrentRoute();
    if (! $currentRoute) {
        return false;
    }
    if ($middleware) {
        return in_array($middleware, $currentRoute->middleware());
    }

    return $currentRoute->middleware() ? true : false;
}

/**
 * Runs query where there are multiple values provided from a comma-separated list.
 * e.g. `filter[tag]=good-quote,hide-in-gallery,good-photo`
 * @param query $query
 * @param string $queryString
 * @param string $filter
 * @return query result
 */
function multipleValueQuery($query, $queryString, $filter)
{
    $values = explode(',', $queryString);

    if (count($values) > 1) {
        // For the first `where` query, we want to limit results... from then on,
        // we want to append (e.g. `SELECT * (WHERE _ OR WHERE _ OR WHERE _)` and (WHERE _ OR WHERE _))
        $query->where(function ($query) use ($values, $filter) {
            foreach ($values as $value) {
                $query->orWhere($filter, $value);
            }
        });
    } else {
        $query->where($filter, $values[0], 'and');
    }
}

/**
 * Returns age of user with given birthdate (or number of full years since given date).
 * @param string $birthdate
 */
function getAgeFromBirthdate($birthdate)
{
    if (! $birthdate) {
        return null;
    }

    $birthdate = new Carbon($birthdate);
    $now = new Carbon();

    return $birthdate->diffInYears($now);
}

/**
 * Helper function to determine where to grab the Northstar ID from.
 * If the request is made by an admin, safe to grab custom user ID.
 * Otherwise, grab Northstar ID from authorized request.
 *
 * @param $request
 */
function getNorthstarId($request)
{
    if (token()->role() === 'admin') {
        return isset($request['northstar_id']) ? $request['northstar_id'] : auth()->id();
    }

    return auth()->id();
}

/**
 * Determines if the user is an admin or staff.
 */
function is_staff_user()
{
    if (token()->exists() && in_array(token()->role, ['admin', 'staff'])) {
        return true;
    }

    return false;
}

/**
 * Get the env vars which are safe for client usage.
 *
 * @return array
 */
function get_client_environment_vars()
{
    return [
        'DS_ENABLE_V3_QUANTITY_SUPPORT' => config('features.v3QuantitySupport'),
    ];
}
