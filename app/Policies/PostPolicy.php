<?php

namespace Rogue\Policies;

use Rogue\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;
use Rogue\Http\Controllers\Traits\AuthorizesWithToken;

class PostPolicy
{
    use AuthorizesWithToken, HandlesAuthorization;

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
     * @param  Illuminate\Contracts\Auth\Authenticatable $user
     * @param  Rogue\Models\Post $post
     * @return bool
     */
    public function viewAll($user, Post $post)
    {
        return $this->allowOwnerStaffOrMachine($user, $post);
    }

    /**
     * Determine if the given post can be seen by the user.
     *
     * @param  Illuminate\Contracts\Auth\Authenticatable $user
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
     * @param  Illuminate\Contracts\Auth\Authenticatable $user
     * @param  Rogue\Models\Post $post
     * @return bool
     */
    public function update($user, Post $post)
    {
        return $this->allowOwnerStaffOrMachine($user, $post);
    }

    /**
     * Determine if the given post can be reviewed by the user.
     *
     * @param  Illuminate\Contracts\Auth\Authenticatable $user
     * @param  Rogue\Models\Post $post
     * @return bool
     */
    public function review($user, Post $post)
    {
        // If this is a machine token, show full model:
        if (token()->exists() && ! token()->id()) {
            return true;
        }

        return is_staff_user();
    }
}
