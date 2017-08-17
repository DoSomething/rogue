<?php

namespace Tests\Http;

use Rogue\Models\Post;
use Rogue\Models\User;
use Tests\BrowserKitTestCase;

class PostTest extends BrowserKitTestCase
{
    /**
     * Test that posts get soft-deleted when hitting the DELETE endpoint.
     *
     * @return void
     */
    public function testDeletingAPost()
    {
        $post = factory(Post::class)->create();

        $this->actingAsAdmin()->delete('posts/' . $post->id);

        $this->assertResponseStatus(200);
        $this->seeSoftDeletedRecord('posts', $post->id);
    }

    /**
     * Test that non-admins can't delete posts.
     *
     * @return void
     */
    public function testUnauthenticatedUserDeletingAPost()
    {
        $user = factory(User::class)->make();
        $post = factory(Post::class)->create();

        $this->actingAs($user)->delete('posts/' . $post->id);

        $this->assertResponseStatus(403);
    }
}
