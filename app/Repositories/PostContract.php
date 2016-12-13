<?php

namespace Rogue\Repositories;

interface PostContract
{
    /**
     * Find the specified resource in the repository.
     *
     * @param  string  $id
     * @return object
     */
    // public function find($id);

    // public function get($northstarId, $campaignId, $campaignRunId);

    /**
     * Get collection of all resources or set of resources by ids.
     *
     * @param  array  $ids
     * @return \Illuminate\Support\Collection
     */
    // public function getAll(array $ids = []);

    public function create($signupId, array $data = []);
}
