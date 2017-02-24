<?php

namespace Rogue\Http\Controllers\Api;

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
        $this->middleware('api');

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
        $postableId = $request['postable_id'];
        $postableType = $request['postable_type'];

        // Check to see if the post has a reaction from this particular user with id of northstar_id.
        $reaction = Reaction::withTrashed()->where(['northstar_id' => $userId, 'postable_id' => $postableId, 'postable_type' => $postableType])->first();

        // If a post does not have a reaction from this user, create a reaction.
        if (is_null($reaction)) {
            $reaction = Reaction::create([
                'northstar_id' => $userId,
                'postable_id' => $postableId,
                'postable_type' => $postableType,
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

        $totalReactions = $this->getTotalPostReactions($postableId, $postableType);

        $meta = [
            'action' => $action,
            'total_reactions' => $totalReactions,
        ];

        return $this->item($reaction, $code, $meta);
    }

    /**
     * Query for total reactions for a post.
     *
     * @param int $postableId
     * @param int $postableType
     * @return int total count
     */
    public function getTotalPostReactions($postableId, $postableType)
    {
        $match = ['postable_id' => $postableId, 'postable_type' => $postableType];

        return Reaction::where($match)->count();
    }
}
