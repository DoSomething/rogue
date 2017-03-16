<?php

use Rogue\Models\Photo;
use Rogue\Models\Post;

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
    public function testCreationAndSoftDeleteForReactions()
    {
        // Create a photo.
        $photo = factory(Photo::class)->create();
        $northstarId = str_random(24);

        // Create a post and associate the photo with it.
        $post = factory(Post::class)->create([
            'postable_id' => $photo->id,
            'postable_type' => 'photo',
        ]);
        $post->content()->associate($photo);
        $post->save();

        $reaction = [
            'northstar_id' => $northstarId,
            'reacionable_id' => $photo->id,
            'reactionable_type' => 'photo',
        ];

        // $this->json('POST', $this->reactionsApiUrl, $reaction);
        $this->json('POST', $this->reactionsApiUrl, $reaction);

        $this->assertResponseStatus(201);

        $this->seeJsonSubset([
            'meta' => [
                'total_reactions' => 1,
            ]
        ]);
    }
}
