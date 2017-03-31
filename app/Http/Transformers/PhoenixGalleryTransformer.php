<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Post;
use League\Fractal\TransformerAbstract;

class PhoenixGalleryTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\Photo $photo
     * @return array
     */
    public function transform(Post $post)
    {
        $signup = $post->signup;
        $result = [
            'id' => $post->postable_id,
            'status' => $post->status,
            'caption' => $post->content->caption,
            'uri' => $post->content->file_url,
            'media' => [
                'uri' => $post->content->file_url,
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
