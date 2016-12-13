<?php

namespace Rogue\Repositories;

interface PostContract
{
    /**
     * Create a post.
     *
     * @param  array $data
     * @param  int  $signupId
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create(array $data = [], $signupId);
}
