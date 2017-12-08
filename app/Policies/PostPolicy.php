<?php

namespace Rogue\Policies;

use Rogue\Post;
use Rogue\Models\Post;
use Rogue\User;
use Rogue\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the post.
     *
     * @param  \Rogue\User  $user
     * @param  \Rogue\Post  $post
     * @return mixed
     */
    public function view(User $user, Post $post)
    {
        dd('im here');
        return token()->exists() && in_array(token()->role, ['admin', 'staff']);
    }
}
