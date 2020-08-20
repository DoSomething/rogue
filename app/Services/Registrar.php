<?php

namespace Rogue\Services;

use DoSomething\Gateway\Northstar;

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
        return $this->northstar->getUser($id);
    }

    /**
     * Finds a group of users in the Rogue/Northstar system.
     *
     * @param  array $ids
     * @return array $users|null
     */
    public function findAll(array $ids = [])
    {
        return $this->getBatchedCollection($ids);
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
        $users = $this->northstar->getAllUsers([
            'search' => $query,
            'page' => $page,
        ]);

        return $users;
    }
}
