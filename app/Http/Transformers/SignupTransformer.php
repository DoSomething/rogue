<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Signup;
use League\Fractal\TransformerAbstract;

class SignupTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'post',
        'event',
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
            'id' => $signup->id,
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
     * Include the event
     *
     * @param \Rogue\Models\Signup $signup
     * @return \League\Fractal\Resource\Item
     */
    public function includeEvent(Signup $signup)
    {
        $event = $signup->event;

        return $this->item($event, new EventTransformer);
    }

    /**
     * Include the post
     *
     * @param \Rogue\Models\Signup $signup
     * @return \League\Fractal\Resource\Collection
     */
    public function includePost(Signup $signup)
    {
        $post = $signup->posts;

        return $this->collection($post, new PostTransformer);
    }
}
