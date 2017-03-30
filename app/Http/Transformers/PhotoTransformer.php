<?php

// @TODO - to be removed when we get rid of this table.

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
            'id' => $photo->id,
            'signup_id' => $photo->post->signup_id,
            'northstar_id' => $photo->post->northstar_id,
            'campaign_id' => $photo->post->signup->campaign_id,
            'campaign_run_id' => $photo->post->signup->campaign_run_id,
            'content' => [
                'type' => 'photo',
                'media' => [
                    'url' => $photo->file_url,
                    'edited_url' => $photo->edited_file_url,
                ],
                'caption' => $photo->caption,
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
