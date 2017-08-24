<?php

namespace Rogue\Repositories;

use Illuminate\Support\Facades\Cache;

class CacheRepository
{
    /**
     * Instantiate a new CacheRepository.
     */
    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string  $key
     * @return mixed
     */
    public function retrieve($key)
    {
        $key = $this->applyPrefix($key);

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
        $idsWithPrefix = [];

        foreach ($keys as $key => $id) {
            array_push($idsWithPrefix, $this->applyPrefix($id));
        }

        $retrieved = [];

        $data = Cache::many($idsWithPrefix);

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
     * Apply a prefix on supplied string used as cache key.
     *
     * @param  string  $string
     * @return string
     */
    protected function applyPrefix($string)
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
        $key = $this->applyPrefix($key);

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
        foreach ($values as $key => $value) {
            $keyWithPrefix = $this->applyPrefix($key);
            $values[$keyWithPrefix] = $values[$key];
            unset($values[$key]);
        }

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
            return str_replace($this->prefix . ':', '', $string);
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
