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
 * e.g. `filter[tag]=good-quote,hide-in-gallery,good-submission`
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
    if (token()->role() === 'admin' || in_array('admin', token()->scopes())) {
        return isset($request['northstar_id']) ? $request['northstar_id'] : auth()->id();
    }

    return auth()->id();
}

/**
 * Determines if the user is an admin or staff.
 */
function is_staff_user()
{
    $isStaffUser = token()->exists() && in_array(token()->role, ['admin', 'staff']);
    $isStaffClient = token()->exists() && in_array('admin', token()->scopes());

    if ($isStaffUser || $isStaffClient) {
        return true;
    }

    return false;
}

/**
 * Parses out ?includes in request.
 *
 * @param $request
 * @param $include str
 */
function has_include($request, $include) {
    if ($request->query('include')) {
        $includes = $request->query('include');

        if (is_string($includes)) {
            $includes = explode(',', $request->query('include'));
        }

        return in_array($include, $includes);
    }

    return false;
}
