<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\Post $post
     * @return array
     */
    public function transform(Post $post)
    {
        $url = ($post->url === 'default') ? 'default' : config('filesystems.disks.s3.public_url') . '/' . config('filesystems.disks.s3.bucket') . '/uploads/reportback-items/edited_' . $post->id . '.jpeg';

        return [
            'id' => $post->id,
            'signup_id' => $post->signup_id,
            'northstar_id' => $post->northstar_id,
            'media' => [
                'url' => $url,
                'caption' => $post->caption,
            ],
            'tagged' => $post->tagNames(),
            'reactions' => $post->reactions,
            'status' => $post->status,
            'source' => $post->source,
            'remote_addr' => $post->remote_addr,
            'created_at' => $post->created_at->toIso8601String(),
            'updated_at' => $post->updated_at->toIso8601String(),
        ];
    }
}
