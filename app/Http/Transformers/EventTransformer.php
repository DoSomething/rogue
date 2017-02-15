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
            'event_type' => $event->event_type,
            'submission_type' => $event->submission_type,
            'quantity' => $event->quantity,
            'quantity_pending' => $event->quantity_pending,
            'why_participated' => $event->why_participated,
            'caption' => $event->caption,
            'status' => $event->status,
            'source' => $event->source,
            'remote_addr' => $event->remote_addr,
            'reason' => $event->reason,
            'created_at' => $event->created_at->toIso8601String(),
            'updated_at' => $event->updated_at->toIso8601String(),
        ];
    }
}
