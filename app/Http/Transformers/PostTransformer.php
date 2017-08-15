<?php

namespace Rogue\Http\Transformers;

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
        return [
            'id' => $post->id,
            'signup_id' => $post->signup_id,
            'northstar_id' => $post->northstar_id,
            'media' => [

                'url' => $post->getMediaUrl(),
                'original_image_url' => $post->url,
                'caption' => $post->caption,
            ],
            // @TODO: include tags
            'status' => $post->status,
            'source' => $post->source,
            'remote_addr' => $post->remote_addr,
            'created_at' => $post->created_at->toIso8601String(),
            'updated_at' => $post->updated_at->toIso8601String(),
        ];
    }

    /**
     * Include the signup.
     *
     * @param \Rogue\Models\Post $post
     * @return \League\Fractal\Resource\Collection
     */
    public function includeSignup(Post $post)
    {
        // Don't include posts but include the user.
        $transformer = (new SignupTransformer)->setDefaultIncludes(['user']);

        return $this->item($post->signup, $transformer);
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
