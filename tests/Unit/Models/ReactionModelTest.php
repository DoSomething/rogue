<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\Signup;
use Rogue\Models\Reaction;

class ReactionModelTest extends TestCase
{
    /**
     * Test that a post & signup's updated_at is touched when a
     * reaction is updated.
     *
     * @return void
     */
    public function testUpdatedPostAndSignupWithReaction()
    {
        $this->mockTime('8/3/2017 14:00:00');

        // Create a signup and a post, and associate them to each other.
        $signup = factory(Signup::class)->create();
        $post = factory(Post::class)->create();
        $post->signup()->associate($signup);

        // And then later on, someone likes the post.
        $this->mockTime('8/3/2017 17:30:00');
        $reaction = factory(Reaction::class)->create([
            'post_id' => $post->id,
        ]);

        // Make sure the signup and post's updated_at matches the reaction created_at time.
        $this->assertEquals($reaction->created_at, $post->fresh()->updated_at);

        // @TODO: Laravel doesn't touch timestamps recursively - only direct relationships.
        // $this->assertEquals($reaction->created_at, $signup->fresh()->updated_at);
        $this->markTestIncomplete();
    }
}
