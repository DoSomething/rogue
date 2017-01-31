<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Photo;
use League\Fractal\TransformerAbstract;

class PhotoTransformer extends TransformerAbstract
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
    public function transform(Photo $photo)
    {
        return [
            'event_id' => $photo->post->event_id,
            'signup_id' => $photo->post->signup_id,
            'northstar_id' => $photo->post->northstar_id,
            'campaign_id' => $photo->post->signup->campaign_id,
            'campaign_run_id' => $photo->post->signup->campaign_run_id,
            'post' => [
                'type' => 'photo',
                'media' => [
                    'url' => $photo->file_url,
                    'edited_url' => $photo->edited_file_url,
                ],
                'caption' => $photo->caption,
                'status' => $photo->status,
                'created_at' => $photo->created_at->toIso8601String(),
                'updated_at' => $photo->updated_at->toIso8601String(),
            ],
        ];
    }

    /**
     * Include the event
     *
     * @param \Rogue\Models\Photo $photo
     * @return \League\Fractal\Resource\Item
     */
    public function includeEvent(Photo $photo)
    {
        $event = $photo->post->event;

        return $this->item($event, new EventTransformer);
    }
}
