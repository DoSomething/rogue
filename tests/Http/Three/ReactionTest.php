<?php

namespace Tests\Http\Three;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\Signup;
use Rogue\Models\Reaction;

class ReactionTest extends TestCase
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
        $post = factory(Post::class)->create();

        $northstarId = $this->faker->uuid;

        // Create a reaction.
        $response = $this->withAccessToken($this->randomUserId(), 'admin')->postJson('api/v3/post/' . $post->id . '/reactions', [
            'northstar_id' => $northstarId,
        ]);

        $response->assertStatus(200);

        // Make sure this creates a reaction.
        $response->assertJson([
            'meta' => [
                'total_reactions' => 1,
            ],
        ]);

        // React (unlike) again to the same post with the same user.
        $response = $this->withAccessToken($this->randomUserId(), 'admin')->postJson('api/v3/post/' . $post->id . '/reactions', [
            'northstar_id' => $northstarId,
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
     * Test that the POST /reactions request without activity scope.
     *
     * POST /reactions
     * @return void
     */
    public function testPostingReactionWithoutActivityScope()
    {
        // Create a post to react to.
        $post = factory(Post::class)->create();

        $northstarId = $this->faker->uuid;

        // Create a reaction.
        $response = $this->postJson('api/v3/post/' . $post->id . '/reactions', [
            'northstar_id' => $northstarId,
        ]);

        $response->assertStatus(401);
        $this->assertEquals('Unauthenticated.', $response->decodeResponseJson()['message']);
    }

    /**
     * Test that the aggregate of total reactions for a post is correct.
     *
     * POST /reactions
     * @return void
     */
    public function testAggregateReactions()
    {
        $post = factory(Post::class)->create();

        // Create a reaction.
        $response = $this->withAccessToken($this->randomUserId(), 'admin')->postJson('api/v3/post/' . $post->id . '/reactions', [
            'northstar_id' => $this->faker->uuid,
        ]);

        // Make sure this creates a reaction.
        $response->assertStatus(200);
        $response->assertJson([
            'meta' => [
                'total_reactions' => 1,
            ],
        ]);

        // A second user reacts to the same post..
        $response = $this->withAccessToken($this->randomUserId(), 'admin')->postJson('api/v3/post/' . $post->id . '/reactions', [
            'northstar_id' => $this->faker->uuid,
        ]);

        // Make sure this creates a reaction and increases total_reaction count.
        $response->assertStatus(200);
        $response->assertJson([
            'meta' => [
                'total_reactions' => 2,
            ],
        ]);
    }

    /**
     * Test that a post and signup's updated_at updates when a reaction is made.
     *
     * @return void
     */
    public function testUpdatedPostAndSignupWithReaction()
    {
        $this->mockTime('8/3/2017 14:00:00');
        $post = factory(Post::class)->create();

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

    /**
     * Test that non-authenticated user's/apps can't create reactions.
     *
     * @return void
     */
    public function testUnauthenticatedUserCreatingAReaction()
    {
        $post = factory(Post::class)->create();

        // Create a reaction.
        $response = $this->postJson('api/v3/post/' . $post->id . '/reactions', [
            'northstar_id' => $this->faker->uuid,
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test for retrieving all reactions of a post.
     *
     * GET /api/v3/post/:post_id/reactions
     * @return void
     */
    public function testReactionsIndex()
    {
        $post = factory(Post::class)->create();
        $post->reactions()->saveMany(
            factory(Reaction::class, 10)->make()
        );

        $response = $this->withAccessToken($this->randomUserId(), 'admin')->getJson('api/v3/post/' . $post->id . '/reactions');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'northstar_id',
                    'post_id',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ],
            ],
        ]);
    }

    /**
     * Test for retrieving all reactions of a post without activity scope.
     *
     * GET /api/v3/post/:post_id/reactions
     * @return void
     */
    public function testReactionsIndexWithoutRequiredScopes()
    {
        $post = factory(Post::class)->create();
        $post->reactions()->saveMany(
            factory(Reaction::class, 10)->make()
        );

        $response = $this->getJson('api/v3/post/' . $post->id . '/reactions');

        $response->assertStatus(403);
        $this->assertEquals('Requires a token with the following scopes: activity', $response->decodeResponseJson()['message']);
    }
}
