<?php

namespace Rogue\Http\Controllers\Traits;

trait FiltersRequests
{
    /**
     * Create a new query builder from the given Eloquent class, which can then be
     * filtered, searched, and/or paginated.
     *
     * @param string $class - Eloquent model class name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery($class)
    {
        return (new $class())->newQuery();
    }

    /**
     * Limit results to users exactly matching a set of filters.
     *
     * @param $query
     * @param $filters
     * @param $indexes - Indexed fields (whitelisted for filtering)
     * @return mixed
     */
    public function filter($query, $filters, $indexes)
    {
        if (!$filters) {
            return $query;
        }

        // If there is an exclude filter, remove it from $filters and save the value.
        if (array_key_exists('exclude', $filters)) {
            $excludedValues = $filters['exclude'];
            unset($filters['exclude']);

            // If there is an exclude_by_field filter, remove from $filters and save the value.
            // Example: filter[exclude]=1200537,3618101&filter[exclude_field]=school_id
            if (array_key_exists('exclude_by_field', $filters)) {
                $excludeByField = $filters['exclude_by_field'];
                unset($filters['exclude_by_field']);
                // Else default to exclude by an 'id' field.
            } else {
                $excludeByField = 'id';
            }
        } else {
            $excludedValues = false;
        }

        // If there is an updated_at param, remove it from $filters and save the value.
        if (array_key_exists('updated_at', $filters)) {
            $updatedAtValue = $filters['updated_at'];
            unset($filters['updated_at']);
        } else {
            $updatedAtValue = false;
        }

        // Requests may be filtered by indexed fields.
        $filters = array_intersect_key($filters, array_flip($indexes));

        // You can filter by multiple values, e.g. `filter[source]=agg,cgg`
        // to get records that have a source value of either `agg` or `cgg`.
        foreach ($filters as $filter => $values) {
            multipleValueQuery($query, $values, $filter);
        }

        if ($updatedAtValue) {
            $query->where('updated_at', '>', $updatedAtValue);
        }

        if ($excludedValues) {
            $query->whereNotIn($excludeByField, explode(',', $excludedValues));
        }

        return $query;
    }

    /**
     * Order results by the given column & direction.
     *
     * @param $query
     * @param $order - The provided 'orderBy' query string.
     * @param $indexes - Whitelisted fields that we can order by.
     * @return mixed
     */
    public function orderBy($query, $orderBy, $indexes)
    {
        if ($orderBy) {
            [$column, $direction] = explode(',', $orderBy, 2);

            if (in_array($column, $indexes)) {
                $query = $query->orderBy($column, $direction);

                // If we have multiple items with the same '$column' value,
                // use ID as a secondary sort column to ensure stable sort.
                if ($column != 'id') {
                    $query->orderBy('id', 'asc');
                }
            }
        } else {
            // If we don't specify an ordering in the query, we should default
            // to order by ID. (Fun fact: MySQL makes no guarantees of ordering
            // if we don't include this in the query!)
            $query->orderBy('id', 'asc');
        }

        return $query;
    }
}
