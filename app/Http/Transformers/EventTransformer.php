<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Event;
use League\Fractal\TransformerAbstract;

class EventTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\Event $event
     * @return array
     */
    public function transform(Event $event)
    {
        return [
            'event_id' => (string) $event->id,
            'event_type' => $event->event_type,
            'submission_type' => $event->submission_type,
            'created_at' => $event->created_at->toIso8601String(),
            'updated_at' => $event->updated_at->toIso8601String(),
        ];
    }
}
