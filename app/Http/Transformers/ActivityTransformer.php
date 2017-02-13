<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Event;
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
        'posts', 'events',
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
            'submission_type' => $signup->event->submission_type,
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
     * Include the post.
     *
     * @param \Rogue\Models\Signup $signup
     * @return \League\Fractal\Resource\Collection
     */
    public function includePosts(Signup $signup)
    {
        $post = $signup->posts;

        return new \League\Fractal\Resource\Collection($post, function ($post) {
            return [
                'postable_id' => $post->postable_id,
                'post_event_id' => $post->event_id,
                'submission_type' => $post->event->submission_type,
                'postable_type' => $post->postable_type,
                'content' => [
                    'media' => [
                        'url' => $post->content->file_url,
                        'edited_url' => $post->content->edited_file_url,
                    ],
                    'caption' => $post->content->caption,
                    'status' => $post->content->status,
                    'remote_addr' => $post->content->remote_addr,
                    'post_source' => $post->content->source,
                    'created_at' => $post->content->created_at->toIso8601String(),
                    'updated_at' => $post->content->updated_at->toIso8601String(),
                ],
            ];
        });
    }

     /**
      * Include the event log for the signup.
      *
      * @param \Rogue\Models\Signup $signup
      * @return \League\Fractal\Resource\Collection
      */
     public function includeEvents(Signup $signup)
     {
         $event = Event::where('signup_id', $signup->id)->get();

         return new \League\Fractal\Resource\Collection($event, function ($event) {
             return [
                'event_id' => $event->id,
                'event_type' => $event->event_type,
                'submission_type' => $event->submission_type,
                'quanity' => $event->quantity,
                'quanity_pending' => $event->quantity_pending,
                'why_participated' => $event->why_participated,
                'caption' => $event->caption,
                'status' => $event->status,
                'source' => $event->source,
                'remote_addr' => $event->remote_addr,
                'reason' => $event->reason,
                'created_at' => $event->created_at->toIso8601String(),
                'updated_at' => $event->updated_at->toIso8601String(),
            ];
         });
     }
}
