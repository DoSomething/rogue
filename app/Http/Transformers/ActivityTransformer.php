<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Signup;
use League\Fractal\TransformerAbstract;


class ActivityTransformer extends TransformerAbstract
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
     * Transform resource data.
     *
     * @param \Rogue\Models\Signup $signup
     * @return array
     */
    public function transform(Signup $signup)
    {
        return [
            'signup_id' => $signup->id,
            'signup_source' => $signup->source,
            'event_id' => $signup->event_id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'campaign_run_id' => $signup->campaign_run_id,
            'quantity' => $signup->quantity,
            'quantity_pending' => $signup->quantity_pending,
            'why_participated' => $signup->why_participated,
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
}
