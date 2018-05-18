<?php

namespace Rogue\Policies;

use Rogue\Models\Post;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Determine if the given post can be seen by the user.
     *
     * @param  Illuminate\Contracts\Auth\Authenticatable $user
     * @param  Rogue\Models\Post $post
     * @return bool
     */
    public function show(Authenticatable $user, Post $post)
    {
        if ($post->status != 'accepted') {
            return is_staff_user() || $user->northstar_id === $post->northstar_id;
        }

        return true;
    }

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  Illuminate\Contracts\Auth\Authenticatable $user
     * @param  Rogue\Models\Post $post
     * @return bool
     */
    public function update(Authenticatable $user, Post $post)
    {
        return is_staff_user() || $user->northstar_id === $post->northstar_id;
    }
}
