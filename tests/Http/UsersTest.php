<?php

namespace Tests\Http;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\Signup;

class UsersTest extends TestCase
{
    /** @test */
    public function it_should_check_authorization()
    {
        $response = $this->withStandardAccessToken()->deleteJson(
            '/api/v3/users/5d3630a0fdce2742ff6c64d4',
        );
        $response->assertStatus(403);
    }

    /** @test */
    public function it_should_delete_users()
    {
        // Create the signups & posts we're going to destroy:
        $post1 = factory(Post::class)
            ->create([
                'northstar_id' => '5d3630a0fdce2742ff6c64d4',
                'details' => '{"hello": "world"}',
            ])
            ->first();
        $post2 = factory(Post::class)
            ->create(['northstar_id' => '5d3630a0fdce2742ff6c64d4'])
            ->first();

        $response = $this->withAdminAccessToken()->deleteJson(
            '/api/v3/users/5d3630a0fdce2742ff6c64d4',
        );
        $response->assertSuccessful();

        // The command should remove signups & posts for this user:
        $this->assertAnonymized($post1);
        $this->assertAnonymized($post2);
    }

    /**
     * Assert that the given post & it's signup have been anonymized.
     *
     * @param Post $post
     */
    protected function assertAnonymized(Post $post)
    {
        // The post and its signup should be soft deleted, and fields that may
        // contain personally-identifiable information should be erased:
        $this->assertSoftDeleted('posts', [
            'id' => $post->id,
            'text' => null,
            'url' => null,
            'details' => null,
        ]);
        $this->assertSoftDeleted('signups', [
            'id' => $post->signup_id,
            'why_participated' => null,
            'details' => null,
        ]);
    }
}
