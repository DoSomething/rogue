<?php

namespace Tests\Http\Api;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\Signup;

class ReactionsApiTest extends TestCase
{
    /**
     * Test that the POST /reactions request creates a reaction for a post.
     * Also test that when POST /reactions is hit again by the same user
     * for the same post, the reaction is soft deleted.
     *
     * POST /reactions
     * @return void
     */
    public function testPostingAndSoftDeleteForReaction()
    {
        // Create a post to react to.
        $signup = factory(Signup::class)->create();
        $signup->posts()->save(factory(Post::class)->make());
        $post = $signup->posts->first();

        $northstarId = $this->faker->uuid;

        // Create a reaction.
        $response = $this->withRogueApiKey()->postJson('api/v2/reactions', [
            'northstar_id' => $northstarId,
            'post_id' => $post->id,
        ]);

        $response->assertStatus(200);

        // Make sure this creates a reaction.
        $response->assertJson([
            'meta' => [
                'total_reactions' => 1,
            ],
        ]);

        // React (unlike) again to the same post with the same user.
        $response = $this->withRogueApiKey()->postJson('api/v2/reactions', [
            'northstar_id' => $northstarId,
            'post_id' => $post->id,
        ]);

        // This should now be a 201 code because it was updated.
        // @TODO update this when we do an audit of status codes in Rogue.
        $response->assertStatus(201);

        // Make sure this reaction is soft deleted.
        $response->assertJson([
            'meta' => [
                'total_reactions' => 0,
            ],
        ]);
    }

    /**
     * Test that the aggregate of total reactions for a post is correct.
     *
     * POST /reactions
     * @return void
     */
    public function testAggregateReactions()
    {
        $signup = factory(Signup::class)->create();
        $signup->posts()->save(factory(Post::class)->make());
        $post = $signup->posts->first();

        // Create a reaction.
        $response = $this->withRogueApiKey()->post('api/v2/reactions', [
            'northstar_id' => $this->faker->uuid,
            'post_id' => $post->id,
        ]);

        // Make sure this creates a reaction.
        $response->assertStatus(200);
        $response->assertJson([
            'meta' => [
                'total_reactions' => 1,
            ],
        ]);

        // A second user reacts to the same post..
        $secondResponse = $this->withRogueApiKey()->postJson('api/v2/reactions', [
            'northstar_id' => $this->faker->uuid,
            'post_id' => $post->id,
        ]);

        // Make sure this creates a reaction and increases total_reaction count.
        $secondResponse->assertStatus(200);
        $secondResponse->assertJson([
            'meta' => [
                'total_reactions' => 2,
            ],
        ]);
    }
}
