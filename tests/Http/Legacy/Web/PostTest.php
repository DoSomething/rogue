<?php

namespace Tests\Http\Legacy\Web;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\User;
use Rogue\Services\Fastly;

class PostTest extends TestCase
{
    /**
     * Test that posts get soft-deleted when hitting the DELETE endpoint.
     *
     * @return void
     */
    public function testDeletingAPost()
    {
        $post = factory(Post::class)->create();

        // Mock the Fastly API calls.
        $this->mock(Fastly::class)
            ->shouldReceive('purgeKey');

        $response = $this->actingAsAdmin()->deleteJson('posts/' . $post->id);

        $response->assertStatus(200);

        $this->assertSoftDeleted('posts', ['id' => $post->id]);
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

        $response = $this->actingAs($user)->deleteJson('posts/' . $post->id);

        $response->assertStatus(403);
    }
}
