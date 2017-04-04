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
            // Add link to review reportback item in Rogue here once that page exists
            // 'uri' => 'link_goes_here'
            'media' => [
                'uri' => $post->url,
                'type' => 'image',
            ],
            'created_at' => $post->created_at->toIso8601String(),
            'reportback' => [
                'id' => $signup->id,
                'created_at' => $signup->created_at->toIso8601String(),
                'updated_at' => $signup->updated_at->toIso8601String(),
                'quantity' => $signup->quantity,
                'why_participated' => $signup->why_participated,
                'flagged' => 'false',
            ],
        ];

        return $result;
    }
}
