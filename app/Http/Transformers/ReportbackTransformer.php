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

        $uri = ($post->url === 'default') ? 'default' : config('filesystems.disks.s3.public_url') . '/' . config('filesystems.disks.s3.bucket') . '/uploads/reportback-items/edited_' . $post->id . '.jpeg';

        $result = [
            'id' => (string) $post->id,
            'status' => $post->status,
            'caption' => $post->caption,
            'uri' => url(config('services.phoenix.uri') . '/api/v1/reportback-items/'.$post->id, ['absolute' => true]),
            'media' => [
                'uri' => $uri,
                'type' => 'image',
            ],
            'tagged' => $post->tagNames(),
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
                                'kudos_id' => ! empty($post->reactions[0]) ? (string) $post->reactions[0]->id : null,
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
