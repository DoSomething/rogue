<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Post;
use Rogue\Models\Reaction;
use Rogue\Http\Requests\ReactionRequest;
use Rogue\Http\Transformers\ReactionTransformer;

class ReactionController extends ApiController
{
    /**
     * @var \Rogue\Http\Transformers\ReactionTransformer.
     */
    protected $reactionTransformer;

    /**
     * Create new ReactionController instance.
     */
    public function __construct()
    {
        $this->transformer = new ReactionTransformer;
    }

    /**
     * Store or update a reportback_item's reaction.
     * Referenced from https://mydnic.be/post/the-perfect-like-system-for-laravel
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReactionRequest $request)
    {
        $userId = $request['northstar_id'];
        $postId = $request['post_id'];

        // Check to see if the post has a reaction from this particular user with id of northstar_id.
        $reaction = Reaction::withTrashed()->where(['northstar_id' => $userId, 'post_id' => $postId])->first();

        // If a post does not have a reaction from this user, create a reaction.
        if (is_null($reaction)) {
            $reaction = Reaction::create([
                'northstar_id' => $userId,
                'post_id' => $postId,
            ]);

            $code = 200;
            $action = 'liked';
        } else {
            // If the post was previously "liked" by this user, soft delete the reaction. Otherwise, restore the reaction.
            $code = 201;
            if (is_null($reaction->deleted_at)) {
                $action = 'unliked';
                $reaction->delete();
            } else {
                $action = 'liked';
                $reaction->restore();
            }
        }

        $meta = [
            'action' => $action,
            'total_reactions' => $this->getTotalReactions($postId),
        ];

        return $this->item($reaction, $code, $meta);
    }

    /**
     * Get the number of reactions a post has.
     *
     * @param int $postId
     * @return int
     */
    private function getTotalReactions($postId)
    {
        return count(Post::find($postId)->reactions);
    }
}
