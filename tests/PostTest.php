<?php

use Rogue\Models\User;
use Rogue\Models\Post;

class PostTest extends TestCase
{
    /**
     * Test that posts get soft deleted when hiting the DELETE endpoint.
     *
     * @return void
     */
    public function testDeletingAPost()
    {
        $user = factory(User::class)->make([
            'role' => 'admin',
        ]);

        $this->be($user);

        $post = factory(Post::class)->create();

        $this->json('DELETE', 'posts/' . $post->id);

        $this->assertResponseStatus(200);

        // Check that the post record is still in the database
        // Also, check that you can't find it with a `deleted_at` column as null.
        $this->seeInDatabase('posts', [
                'id' => $post->id,
                'url' => null,
            ])->notSeeInDatabase('posts', [
                'id' => $post->id,
                'deleted_at' => null,
            ]);
    }

    /**
     * Test that non-admins can't delete posts.
     *
     * @return void
     */
    public function testUnauthenticatedUserDeletingAPost()
    {
        $user = factory(User::class)->make();

        $this->be($user);

        $post = factory(Post::class)->create();

        $this->json('DELETE', 'posts/' . $post->id);

        $this->assertResponseStatus(403);
    }
}
