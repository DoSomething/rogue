<?php

use Rogue\Models\Photo;

class ReactionsApiTest extends TestCase
{
    /*
     * Base URL for the Api.
     */
    protected $reactionsApiUrl = 'api/v2/reactions';

    /**
     * Test that the POST /reactions request creates a reaction for a reactionable.
     * Also test that when POST /reactions is hit again by the same user for the same reactionable,
     * the reaction is soft deleted.
     *
     * POST /reactions
     * @return void
     */
    public function testPostingAndSoftDeleteForReaction()
    {
        // Create a photo to react to.
        $photo = factory(Photo::class)->create();

        $northstarId = str_random(24);

        $reaction = [
            'northstar_id' => $northstarId,
            'reactionable_id' => $photo->id,
            'reactionable_type' => 'photo',
        ];

        $this->json('POST', $this->reactionsApiUrl, $reaction);

        $this->assertResponseStatus(200);

        // Make sure this creates a reaction.
        $this->seeJsonSubset([
            'meta' => [
                'total_reactions' => 1,
            ]
        ]);

        // React (unlike) again to the same photo with the same user.
        $this->json('POST', $this->reactionsApiUrl, $reaction);

        // This should now be a 201 code because it was updated.
        $this->assertResponseStatus(201);

        // Make sure this reaction is soft deleted.
        $this->seeJsonSubset([
            'meta' => [
                'total_reactions' => 0,
            ]
        ]);
    }

    /**
     * Test that the aggregate of total reactions for a photo is correct.
     *
     * POST /reactions
     * @return void
     */
    public function testAggregateReactions()
    {
        // Create a photo to react to.
        $photo = factory(Photo::class)->create();

        $reaction = [
            'northstar_id' => str_random(24),
            'reactionable_id' => $photo->id,
            'reactionable_type' => 'photo',
        ];

        $this->json('POST', $this->reactionsApiUrl, $reaction);

        $this->assertResponseStatus(200);

        // Make sure this creates a reaction.
        $this->seeJsonSubset([
            'meta' => [
                'total_reactions' => 1,
            ]
        ]);

        $reaction = [
            'northstar_id' => str_random(24),
            'reactionable_id' => $photo->id,
            'reactionable_type' => 'photo',
        ];

        $this->json('POST', $this->reactionsApiUrl, $reaction);

        $this->assertResponseStatus(200);

        // Make sure this creates a reaction and increases total_reaction count.
        $this->seeJsonSubset([
            'meta' => [
                'total_reactions' => 2,
            ]
        ]);
    }
}
