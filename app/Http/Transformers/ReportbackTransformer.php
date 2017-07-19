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
            'id' => $post->id,
            'status' => $post->status,
            'caption' => $post->caption,
            'uri' => url(config('services.phoenix.uri') . '/api/v1/reportback-items/'.$post->id, ['absolute' => true]),
            'media' => [
                'uri' => config('filesystems.disks.s3.public_url') . '/' . config('filesystems.disks.s3.bucket') . '/uploads/reportback-items/edited_' . $post->id . '.jpeg',
                'type' => 'image',
            ],
            'tagged' => $post->tagNames(),
            'created_at' => (string) $post->created_at->timestamp, // Phoenix quirk, this field is a string timestamp.
            'reportback' => [
                'id' => $signup->id,
                'created_at' => $signup->created_at->toIso8601String(),
                'updated_at' => $signup->updated_at->toIso8601String(),
                'quantity' => $signup->quantity,
                'why_participated' => $signup->why_participated,
                'flagged' => 'false',
            ],
            'kudos' => [
                    'data' => [
                        [
                            'current_user' => [
                                'kudos_id' => 1,
                                // `$post->reactions` is constrained to only reactions w/ `as_user` Northstar ID.
                                'reacted' => $post->reactions->count() >= 1,
                            ],
                            'term' => [
                                'id' => 641,
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
