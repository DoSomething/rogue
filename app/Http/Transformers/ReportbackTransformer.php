<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Post;
use League\Fractal\TransformerAbstract;

class ReportbackTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\Post $post
     * @return array
     */
    public function transform(Post $post)
    {
        $signup = $post->signup;

        $result = [
            'id' => (string) $post->id,
            'status' => $post->status,
            'caption' => $post->caption,
            'uri' => url(config('services.phoenix.uri') . '/api/v1/reportback-items/'.$post->id, ['absolute' => true]),
            'media' => [
                'uri' => $post->getMediaUrl(),
                'type' => 'image',
            ],
            'tagged' => $post->tags()->pluck('tag_names'),
            'created_at' => (string) $post->created_at->timestamp, // Phoenix quirk, this field is a string timestamp.
            'reportback' => [
                'id' => (string) $signup->id,
                'created_at' => $signup->created_at->toIso8601String(),
                'updated_at' => $signup->updated_at->toIso8601String(),
                'quantity' => $signup->quantity,
                'why_participated' => $signup->why_participated,
                'flagged' => 'false',
            ],
            'campaign' => [
                'id' => $signup->campaign_id,
            ],
            'kudos' => [
                    'data' => [
                        [
                            'current_user' => [
                                // `$post->reactions` is constrained to only reactions w/ `as_user` Northstar ID.
                                'kudos_id' => ! empty($post->reactions[0]) ? $post->reactions[0]->post_id . '|' . $post->reactions[0]->northstar_id : null,
                                'reacted' => ! empty($post->reactions[0]),
                            ],
                            'term' => [
                                'id' => (string) 641,
                                'name' => 'heart',
                                'total' => $post->reactions_count,
                            ],
                        ],
                    ],
            ],
            'user' => [
                'id' => $signup->northstar_id,
            ],
        ];

        return $result;
    }
}
