<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Post;
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
            'postable_id' => $post->postable_id,
            'post_event_id' => $post->event_id,
            'submission_type' => $post->event->submission_type,
            'postable_type' => $post->postable_type,
            'status' => $post->status,
            'remote_addr' => $post->remote_addr,
            'post_source' => $post->source,
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
