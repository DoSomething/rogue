<?php

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
 * Helper function to increment transaction id.
 *
 * @param \Illuminate\Http\Request $request
 * @return $newTransactionId
 */
function incrementTransactionId($request)
{
    $transactionId = $request->header('X-Request-ID');

    if ($transactionId) {
        $transactionIdParts = explode('-', $transactionId);
        $incrementedStep = $transactionIdParts[1] + 1;
        $newTransactionId = $transactionIdParts[0] . '-' . $incrementedStep;

        return $newTransactionId;
    }

    return null;
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

function getAgeFromBirthdate($birthdate)
{
    // Make DateTime for right now
    $now = new DateTime('now');

    // Make DateTime for birthday
    $birthday = new DateTime();
    $birthday->setTimestamp(strtotime($birthdate));

    // Calculate the difference and return the years
    return $now->diff($birthday)->y;
}
