<?php

namespace Rogue\Http\Transformers;

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
        'posts',
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
     * Include the post
     *
     * @param \Rogue\Models\Signup $signup
     * @return \League\Fractal\Resource\Collection
     */
    public function includePosts(Signup $signup)
    {
        $post = $signup->posts;

        return new \League\Fractal\Resource\Collection($post, function($post) {

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
}
