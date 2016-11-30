<?php

namespace Rogue\Services;

use \DoSomething\Gateway\Northstar;
use Rogue\Repositories\CacheRepository;

class Registrar
{

    /**
     * Create new Registrar instance.
     *
     */
    public function __construct()
    {
        $this->northstar = gateway('northstar');
        $this->cache = new CacheRepository;
    }

    public function find($id)
    {
        // First look in cache
        $user = $this->cache->retrieve($id);

        // If not look to Northstar and store in cache
        if (! $user)
        {
            $user = $this->northstar->getUser('id', $id);

            // @TODO - How long should we store users in Cache?
            $this->cache->store($user->id, $user);
        }

        return $user;
    }

    public function findAll(array $ids = [])
    {
        if ($ids) {
            $users = $this->cache->retrieveMany($ids);

            if (! $users) {
                $users = $this->getBatchedCollection($ids);

                if (count($users)) {
                    $group = $users->keyBy('id')->all();

                    $this->cache->storeMany($group);
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
