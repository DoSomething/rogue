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
     * Determine if the given post can be updated by the user.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function update(Post $post)
    {
        return is_staff_user() || auth()->id() === $post->northstar_id;
    }
}
