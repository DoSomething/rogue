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
        $content = [];
        $contentString = substr($event->content, 1, -1);
        $contentStringWithNoQuotations = str_replace('"', '', $contentString);
        $contentArray = explode(',', $contentStringWithNoQuotations);

        foreach ($contentArray as $attribute) {
            $attributeParts = explode(':', $attribute);
            $content[$attributeParts[0]] = $attributeParts[1];
        }

        return [
            'event_id' => $event->id,
            'eventable_id' => $event->eventable_id,
            'event_type' => $event->eventable_type,
            'content' => $content,
            'user' => $event->user,
            'created_at' => $event->created_at->toIso8601String(),
            'updated_at' => $event->updated_at->toIso8601String(),
        ];
    }
}
