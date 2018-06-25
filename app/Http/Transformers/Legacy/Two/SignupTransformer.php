<?php

namespace Rogue\Http\Transformers\Legacy\Two;

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
        return [
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'campaign_run_id' => $signup->campaign_run_id,
            'quantity' => $signup->getQuantity(),
            'why_participated' => $signup->why_participated,
            'signup_source' => $signup->source,
            'source_details' => $signup->source_details,
            'details' => $signup->details,
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
