<?php

namespace Rogue\Services;

use DoSomething\Gateway\Northstar;
use Rogue\Repositories\CacheRepository;

class Registrar
{
    /**
     * Create new Registrar instance.
     *
     * @param Northstar $northstar
     */
    public function __construct(Northstar $northstar)
    {
        $this->northstar = $northstar;
        $this->cache = new CacheRepository('user');
    }

    /**
     * Finds a single user in the Rogue/Northstar system. If the user is not found
     * in the Cache, then we grab it directly from Northstar and store in cache.
     *
     * @param  string $id
     * @return object $user Northstar user object
     */
    public function find($id)
    {
        $user = $this->cache->retrieve($id);

        if (! $user) {
            $user = $this->northstar->asClient()->getUser('id', $id);

            if ($user) {
                // @TODO - How long should we store users in Cache?
                $this->cache->store($user->id, $user);
            }
        }

        return $user;
    }

    /**
     * Finds a group of users in the Rogue/Northstar system.
     *
     * @param  array $ids
     * @return array $users|null
     */
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
                $users[$key] = $this->find($this->cache->unsetPrefix($key));
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

    /**
     * Search for users in Northstar.
     *
     * @param  string $query
     * @param  int $page
     * @return collection|null
     */
    public function search($query, $page = 1)
    {
        // Attempt to fetch all users.
        $users = $this->northstar->getAllUsers([
            'search' => [
                '_id' => $query,
                'drupal_id' => $query,
                'email' => $query,
                'mobile' => $query,
            ],
            'page' => $page,
        ]);

        return $users;
    }
}
