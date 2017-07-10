<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Signup;
use Rogue\Services\Registrar;
use League\Fractal\TransformerAbstract;

class SignupTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'posts',
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'user',
    ];

    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\Signup $signup
     * @return array
     */
    public function transform(Signup $signup)
    {
        // @TODO - This is temporary. We have migrated data that has stored quanity in the
        // quanity_pending column on the signup. However, since then we updated the business
        // logic to store everything in the quanity column and not use the quanity_pending
        // column at all. We only want to return what is in the quanity_pending column
        // if is the only place quanity is stored.
        if (! is_null($signup->quantity_pending) && is_null($signup->quantity)) {
            $quantity = $signup->quantity_pending;
        } else {
            $quantity = $signup->quantity;
        }

        return [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'campaign_run_id' => $signup->campaign_run_id,
            'quantity' => $quantity,
            'why_participated' => $signup->why_participated,
            'signup_source' => $signup->source,
            'competition' => $signup->competition,
            'created_at' => $signup->created_at->toIso8601String(),
            'updated_at' => $signup->updated_at->toIso8601String(),
        ];
    }

    /**
     * Include the post
     *
     * @param \Rogue\Models\Signup $signup
     * @return \League\Fractal\Resource\Collection
     */
    public function includePosts(Signup $signup)
    {
        $post = $signup->posts;

        return $this->collection($post, new PostTransformer);
    }

    /**
     * Include the user data (optionally)
     *
     * @param \Rogue\Models\Signup $signup
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Signup $signup)
    {
        $registrar = app(Registrar::class);
        $northstar_id = $signup->northstar_id;

        return $this->item($registrar->find($northstar_id), new UserTransformer);
    }
}
