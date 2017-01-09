<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'event',
    ];

    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\Photo $photo
     * @return array
     */
    public function transform(Post $post)
    {
        return [
            'event_id' => $post->event_id,
            'signup_id' => $post->signup_id,
            'northstar_id' => $post->postData->northstar_id,
            'campaign_id' => $post->signup->campaign_id,
            'campaign_run_id' => $post->signup->campaign_run_id,
            'content' => [
                'type' => $post->event->event_type,
                'media' => [
                    'url' => $post->postData->file_url,
                    'edited_url' => $post->postData->edited_file_url,
                ],
                'caption' => $post->postData->caption,
                'status' => $post->postData->status,
                'created_at' => $post->postData->created_at->toIso8601String(),
                'updated_at' => $post->postData->updated_at->toIso8601String(),
            ],
        ];
    }

    /**
     * Include the event
     *
     * @param \Rogue\Models\Post $post
     * @return \League\Fractal\Resource\Item
     */
    public function includeEvent(Post $post)
    {
        $event = $post->event;

        return $this->item($event, new EventTransformer);
    }
}
