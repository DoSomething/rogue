<?php

namespace Rogue\Policies;

use ;
use Rogue\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the post.
     *
     * @param  \  $user
     * @param  \Rogue\Post  $post
     * @return mixed
     */
    public function view( $user, Post $post)
    {
        //
    }

    /**
     * Determine whether the user can create posts.
     *
     * @param  \  $user
     * @return mixed
     */
    public function create( $user)
    {
        //
    }

    /**
     * Determine whether the user can update the post.
     *
     * @param  \  $user
     * @param  \Rogue\Post  $post
     * @return mixed
     */
    public function update( $user, Post $post)
    {
        //
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param  \  $user
     * @param  \Rogue\Post  $post
     * @return mixed
     */
    public function delete( $user, Post $post)
    {
        //
    }
}
