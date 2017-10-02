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
            'event_id' => $event->id,
            'eventable_id' => $event->eventable_id,
            'event_type' => $event->eventable_type,
            'content' => $event->content,
            'user' => $event->user,
            'created_at' => $event->created_at->toIso8601String(),
            'updated_at' => $event->updated_at->toIso8601String(),
        ];
    }
}
