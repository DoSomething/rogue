<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Event;
use Rogue\Services\Registrar;
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
        'posts',
        'events',
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
            'signup_event_id' => $signup->event_id,
            'submission_type' => $signup->events->first()->submission_type,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'campaign_run_id' => $signup->campaign_run_id,
            'quantity' => $signup->quantity,
            'quantity_pending' => $signup->quantity_pending,
            'why_participated' => $signup->why_participated,
            'signup_source' => $signup->source,
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
     * Include the event
     *
     * @param \Rogue\Models\Signup $signup
     * @return \League\Fractal\Resource\Item
     */
    public function includeEvents(Signup $signup)
    {
        $event = $signup->events;

        return $this->collection($event, new EventTransformer);
    }

    /**
     * Include the user data (optionally)
     *
     * @param \Rogue\Models\Signup $signup
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Signup $signup)
    {
        $registrar = new Registrar();
        $northstar_id = $signup->northstar_id;

        return $this->item($registrar->find($northstar_id), new UserTransformer);

    }
}
