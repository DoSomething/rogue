<?php

namespace Rogue\Http\Transformers;

use Gate;
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
        $response = [
            'id' => $post->id,
            'signup_id' => $post->signup_id,
            'type' => $post->type,
            'action' => $post->action,
            'northstar_id' => $post->northstar_id,
            // Add cache-busting query string to urls to make sure we get the
            // most recent version of the image.
            // @NOTE - Remove if we get rid of rotation.
            'media' => [
                'url' => $post->getMediaUrl(),
                'original_image_url' => $post->url . '?time='. Carbon::now()->timestamp,
                'text' => $post->text,
            ],
            'quantity' => $post->quantity,
            'reactions' => [
                'reacted' => ! empty($post->reaction),
                'total' => $post->reactions_count,
            ],
            'status' => $post->status,
            'created_at' => $post->created_at->toIso8601String(),
            'updated_at' => $post->updated_at->toIso8601String(),
        ];

        if (Gate::allows('viewAll', $post)) {
            $response['tags'] = $post->tagSlugs();
            $response['source'] = $post->source;
            $response['source_details'] = $post->source_details;
            $response['remote_addr'] = $post->remote_addr;
            $response['details'] = $post->details;
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
