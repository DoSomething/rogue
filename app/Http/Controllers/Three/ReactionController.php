<?php

namespace Rogue\Http\Controllers\Three;

use Rogue\Models\Post;
use Rogue\Models\Reaction;
use Illuminate\Http\Request;
use Rogue\Http\Controllers\Api\ApiController;
use Rogue\Http\Transformers\Three\ReactionTransformer;

class ReactionController extends ApiController
{
    /**
     * @var \League\Fractal\TransformerAbstract;
     */
    protected $transformer;

    /**
     * Create a controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->transformer = new ReactionTransformer;

        $this->middleware('auth.api');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Rogue\Models\Post $post
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        $this->validate($request, [
            'northstar_id' => 'required|string',
        ]);

        $userId = $request['northstar_id'];
        // Check to see if the post has a reaction from this particular user with id of northstar_id.
        $reaction = Reaction::withTrashed()->where(['northstar_id' => $userId, 'post_id' => $post->id])->first();

        // If a post does not have a reaction from this user, create a reaction.
        if (is_null($reaction)) {
            $reaction = Reaction::create([
                'northstar_id' => $userId,
                'post_id' => $post->id,
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
            'total_reactions' => $post->getTotalReactions($post->id),
        ];

        return $this->item($reaction, $code, $meta);
    }
}
