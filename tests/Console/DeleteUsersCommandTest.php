<?php

namespace Tests\Console;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\Signup;

class DeleteUsersCommandTest extends TestCase
{
    /** @test */
    public function it_should_delete_users()
    {
        // Create the signups & posts we're going to destroy:
        $post1 = factory(Post::class)->create(['northstar_id' => '5d3630a0fdce2742ff6c64d4', 'details' => '{"hello": "world"}'])->first();
        $post2 = factory(Post::class)->create(['northstar_id' => '5d3630a0fdce2742ff6c64d5'])->first();
        $post3 = factory(Post::class)->create(['northstar_id' => '5d3630a0fdce2742ff6c64d5'])->first();

        // Run the 'rogue:delete' command on the 'example-delete-input.csv' file:
        $this->artisan('rogue:delete', ['input' => 'tests/Console/example-delete-input.csv']);

        // The command should remove signups & posts for those users:
        $this->assertAnonymized($post1);
        $this->assertAnonymized($post2);
        $this->assertAnonymized($post3);
    }

    /**
     * Assert that the given model has been anonymized.
     *
     * @param User $user
     */
    protected function assertAnonymized($post)
    {
        // The post and its signup should be soft deleted, and fields that may
        // contain personally-identifiable information should be erased:
        $this->assertSoftDeleted('posts', ['id' => $post->id, 'text' => null, 'url' => null, 'location' => null, 'details' => null]);
        $this->assertSoftDeleted('signups', ['id' => $post->signup_id, 'why_participated' => null, 'details' => null]);
    }
}
