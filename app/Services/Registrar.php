<?php

namespace Rogue\Services;

use \DoSomething\Gateway\Northstar;
use Illuminate\Support\Facades\Cache;

class Registrar
{
    /**
     * Create new Registrar instance.
     *
     */
    public function __construct()
    {
        $this->northstar = gateway('northstar');
    }

    public function find($id)
    {
        // First look in cache
        $user = Cache::get($id);

        // If not look to Northstar and store in cache
        if (! $user)
        {
            $user = $this->northstar->getUser('id', $id);

            Cache::put($user->id, $user, 15);
        }

        return $user;
    }

    public function findAll(array $ids = [])
    {
        if ($ids) {
            $users = $this->retrieveMany($ids);

            if (! $users) {
                $users = $this->getBatchedCollection($ids);

                if (count($users)) {
                    $group = $users->keyBy('id')->all();

                    Cache::putMany($group, 15);
                }
            } else {
                $users = $this->resolveMissingUsers($users);
                $users = collect(array_values($users));
            }

            return $users;
        }

        return null;
    }

    /**
     * @todo - MOVE TO HELPER CLASS (REPO?)
     * Remove all items from the cache.
     *
     * @return void
     */
    public function flush()
    {
        Cache::flush();
    }

    /**
     * @todo - MOVE TO HELPER CLASS (REPO?)
     * Retrieve multiple items from the cache by key.
     * Items not found in the cache will have a null value.
     *
     * @param  array  $keys
     * @return array|null
     */
    protected function retrieveMany(array $keys)
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

        return null;
    }

    /**
     * @todo - MOVE TO HELPER CLASS (REPO?)
     * Resolving missing cached users in a user cache collection.
     *
     * @param  array $users
     * @return array
     */
    protected function resolveMissingUsers($users)
    {
        foreach ($users as $key => $value) {
            if ($value === false or $value === null) {
                $users[$key] = $this->find($key);
            }
        }

        return $users;
    }

    /**
     * Get large number of users in batches from Northstar.
     *
     * @param  array  $ids
     * @param  int $size
     * @return \Illuminate\Support\Collection
     */
    protected function getBatchedCollection($ids, $size = 50)
    {
        // @TODO: Should this be a function in Northstar Client?
        $count = intval(ceil(count($ids) / 50));
        $index = 0;
        $data = [];

        for ($i = 0; $i < $count; $i++) {
            $batch = array_slice($ids, $index, $size);

            $parameters['limit'] = '50';
            $parameters['filter[_id]'] = implode(',', $batch);

            $accounts = $this->northstar->getAllUsers($parameters);

            $data = array_merge($data, $accounts->toArray());

            $index += $size;
        }

        return collect($data);
    }

}
