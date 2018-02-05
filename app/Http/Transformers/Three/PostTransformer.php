<?php

namespace Rogue\Http\Transformers\Three;

use Carbon\Carbon;
use Rogue\Models\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'signup',
        'siblings',
    ];

    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\Post $post
     * @return array
     */
    public function transform(Post $post)
    {
        $reacted = false;
        if ($post->relationLoaded('reactions')) {
            $reacted = $post->reactions->isNotEmpty();
        }

        $response = [
            'id' => $post->id,
            'signup_id' => $post->signup_id,
            'northstar_id' => $post->northstar_id,
            'type' => $post->type,
            'action' => $post->action,
            // Add cache-busting query string to urls to make sure we get the
            // most recent version of the image.
            // @NOTE - Remove if we get rid of rotation.
            'media' => [
                'url' => $post->getMediaUrl(),
                'original_image_url' => $post->url . '?time='. Carbon::now()->timestamp,
                'caption' => $post->caption,
            ],
            'quantity' => $post->quantity,
            'reactions' => [
                'reacted' => $reacted,
                'total' => isset($post->reactions_count) ? $post->reactions_count : null,
            ],
            'status' => $post->status,
            'details' => $post->details,
            'created_at' => $post->created_at->toIso8601String(),
            'updated_at' => $post->updated_at->toIso8601String(),
        ];

        if (is_staff_user() || auth()->id() === $post->northstar_id) {
            $response['tags'] = $post->tagSlugs();
            $response['source'] = $post->source;
            $response['remote_addr'] = $post->remote_addr;
        }

        return $response;
    }

    /**
     * Include the signup.
     *
     * @param \Rogue\Models\Post $post
     * @return \League\Fractal\Resource\Collection
     */
    public function includeSignup(Post $post)
    {
        return $this->item($post->signup, new SignupTransformer);
    }

    /**
     * Include the siblings.
     *
     * @param \Rogue\Models\Post $post
     * @return \League\Fractal\Resource\Collection
     */
    public function includeSiblings(Post $post)
    {
        return $this->collection($post->siblings, new self);
    }
}
