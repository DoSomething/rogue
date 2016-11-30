<?php

namespace Rogue\Repositories;

use Illuminate\Support\Facades\Cache;

class CacheRepository
{
    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string  $key
     * @return mixed
     */
    public function retrieve($key)
    {
        return Cache::get($key);
    }

    /**
     * Retrieve multiple items from the cache by key.
     * Items not found in the cache will have a null value.
     *
     * @param  array  $keys
     * @return array|null
     */
    public function retrieveMany(array $keys)
    {
        $retrieved = [];

        $data = Cache::many($keys);

        foreach ($data as $item) {
            if ($item) {
                $retrieved[] = $item;
            }
        }

        if (count($retrieved)) {
            return $data;
        }
    }

    /**
     * Set a prefix on supplied string used as cache key.
     *
     * @param  string  $string
     * @return string
     */
    public function setPrefix($string)
    {
        if (property_exists($this, 'prefix')) {
            return $this->prefix . ':' . $string;
        } else {
            return $string;
        }
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  int     $minutes
     * @return void
     */
    public function store($key, $value, $minutes = 15)
    {
        Cache::put($key, $value, $minutes);
    }

    /**
     * Store multiple items in the cache for a given number of minutes.
     *
     * @param  array  $values
     * @param  int  $minutes
     * @return void
     */
    public function storeMany(array $values, $minutes = 15)
    {
        Cache::putMany($values, $minutes);
    }

    /**
     * Unset a prefix on supplied string used as cache key.
     *
     * @param  string  $string
     * @return string
     */
    public function unsetPrefix($string)
    {
        if (property_exists($this, 'prefix')) {
            return str_replace('campaign:', '', $string);
        } else {
            return $string;
        }
    }

    /**
     * Resolving missing cached items in cache collection.
     *
     * @param  array $items
     * @return array
     */
    public function resolveMissingItems($items)
    {
        // @TODO: maybe this should run in the retrieveMany() method?
        // @TODO: May want to check if calling Class has find() method using method_exists($object, $method_name).
        // @TODO: Might also be faster to collect the missing items and do a getAll() instead of find() for each individually.

        foreach ($items as $key => $value) {
            if ($value === false || $value === null) {
                if (property_exists($this, 'prefix')) {
                    $id = $this->unsetPrefix($key);
                }

                $items[$key] = $this->find($id);
            }
        }

        return $items;
    }
}
