<?php

use Rogue\Models\Post;
use Faker\Generator;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ReactionsApiTest extends TestCase
{
    use WithoutMiddleware;

    /*
     * Base URL for the Api.
     */
    protected $reactionsApiUrl = 'api/v2/reactions';

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
        $post = factory(Post::class)->create();

        $northstarId = $this->faker->uuid;

        // Create a reaction.
        $this->json('POST', $this->reactionsApiUrl, [
            'northstar_id' => $northstarId,
            'post_id' => $post->id,
        ]);

        $this->assertResponseStatus(200);

        // Make sure this creates a reaction.
        $this->seeJsonSubset([
            'meta' => [
                'total_reactions' => 1,
            ]
        ]);

        // React (unlike) again to the same photo with the same user.
         $this->json('POST', $this->reactionsApiUrl, [
            'northstar_id' => $northstarId,
            'post_id' => $post->id,
        ]);

        // This should now be a 201 code because it was updated.
        // @TODO update this when we do an audit of status codes in Rogue.
        $this->assertResponseStatus(201);

        // Make sure this reaction is soft deleted.
        $this->seeJsonSubset([
            'meta' => [
                'total_reactions' => 0,
            ]
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
        // Create a photo to react to.
        $post = factory(Post::class)->create();

        // Create a reaction.
        $this->json('POST', $this->reactionsApiUrl, [
            'northstar_id' => $this->faker->uuid,
            'post_id' => $post->id,
        ]);

        $this->assertResponseStatus(200);

        // Make sure this creates a reaction.
        $this->seeJsonSubset([
            'meta' => [
                'total_reactions' => 1,
            ]
        ]);

        // A second user reacts to the same photo.
        $this->json('POST', $this->reactionsApiUrl, [
            'northstar_id' => $this->faker->uuid,
            'post_id' => $post->id,
        ]);

        $this->assertResponseStatus(200);

        // Make sure this creates a reaction and increases total_reaction count.
        $this->seeJsonSubset([
            'meta' => [
                'total_reactions' => 2,
            ]
        ]);
    }
}
