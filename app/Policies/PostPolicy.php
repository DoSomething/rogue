<?php

namespace Rogue\Policies;

use Rogue\Models\Post;
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
     * Determine if the full post should be displayed.
     *
     * @param  $user
     * @param  Rogue\Models\Post $post
     * @return bool
     */
    public function viewAll($user, Post $post)
    {
        if ($user === null) {
            return false;
        }

        return is_staff_user() || $user->northstar_id === $post->northstar_id;
    }

    /**
     * Determine if the given post can be seen by the user.
     *
     * @param  $user
     * @param  Rogue\Models\Post $post
     * @return bool
     */
    public function show($user, Post $post)
    {
        if ($post->status !== 'accepted' && $user !== null) {
            return is_staff_user() || $user->northstar_id === $post->northstar_id;
        } elseif ($user === null && $post->status !== 'accepted') {
            return false;
        }

        return true;
    }

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  $user
     * @param  Rogue\Models\Post $post
     * @return bool
     */
    public function update($user, Post $post)
    {
        return is_staff_user() || $user->northstar_id === $post->northstar_id;
    }
}
