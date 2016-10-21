<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Reaction;
use Rogue\Http\Requests\ReactionRequest;
use Illuminate\Http\Request;

class ReactionController extends ApiController
{
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

        // Check to see if the reportback_item has a reaction.
        $existingReaction = Reaction::withTrashed()->where(['northstar_id' => $userId, 'reportback_item_id' => $reportbackItemId])->first();

        // If a reportback_item does not have a reaction, create a reaction.
        if (is_null($existingReaction)) {
            Reaction::create([
                'northstar_id' => $userId,
                'reportback_item_id' => $reportbackItemId,
            ]);
        } else {
        // If the reportback_item is "liked", soft delete the item. Otherwise, restore the reaction.
            if (is_null($existingReaction->deleted_at)) {
                $existingReaction->delete();
            } else {
                $existingReaction->restore();
            }
        }

        // TODO: When we build out Phoenix and decide on what we want in the response, we will need to add a Reaction Transformer.
        return 'Placeholder response.';
    }
}
