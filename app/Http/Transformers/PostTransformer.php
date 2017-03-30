<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Post;
use Rogue\Models\Photo;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\Photo $photo
     * @return array
     */
    public function transform(Post $post)
    {
        return [
            'id' => $post->id,
            'signup_id' => $post->signup_id,
            'northstar_id' => $post->northstar_id,
            'postable_id' => $post->postable_id,
            'postable_type' => $post->postable_type,
            'status' => $post->status,
            'post_source' => $post->source,
            'remote_addr' => $post->remote_addr,
            'content' => [
                'media' => [
                    'url' => $post->content->file_url,
                    'edited_url' => $post->content->edited_file_url,
                ],
                'caption' => $post->content->caption,
                'total_reactions' => count($post->content->reactions),
                'created_at' => $post->content->created_at->toIso8601String(),
                'updated_at' => $post->content->updated_at->toIso8601String(),
            ],
        ];
    }
}
