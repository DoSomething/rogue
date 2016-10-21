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
        $this->reactionTransformer = new ReactionTransformer;
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
        $reportbackItemId = $request['reportback_item_id'];

        // Check to see if the reportback_item has a reaction from this particular user with id of northstar_id.
        $reaction = Reaction::withTrashed()->where(['northstar_id' => $userId, 'reportback_item_id' => $reportbackItemId])->first();

        // If a reportback_item does not have a reaction from this user, create a reaction.
        if (is_null($reaction)) {
            Reaction::create([
                'northstar_id' => $userId,
                'reportback_item_id' => $reportbackItemId,
            ]);
            $code = 200;
            $action = 'liked';
        } else {
            // If the reportback_item was previously "liked" by this user, soft delete the reaction. Otherwise, restore the reaction.
            if (is_null($reaction->deleted_at)) {
                $code = 201;
                $action = "unliked";
                $reaction->delete();
            } else {
                $code = 201;
                $action = "liked";
                $reaction->restore();
            }
        }

        $meta = [
            'action' => $action,
        ];

        return $this->item($reaction, $code, $meta, $this->reactionTransformer);
    }
}
