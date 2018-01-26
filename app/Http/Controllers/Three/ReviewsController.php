<?php

namespace Rogue\Http\Controllers\Three;

use Rogue\Models\Post;
use Illuminate\Http\Request;
use Rogue\Repositories\Three\PostRepository;
// use Rogue\Http\Requests\ReviewsApiRequest;
use Rogue\Http\Controllers\Api\ApiController;
use Rogue\Http\Transformers\Three\PostTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;

class ReviewsController extends ApiController
{
    /**
     * The post service instance.
     *
     * @var Rogue\Repositories\ThreePostRepository
     */
    protected $post;

    /**
     * @var \Rogue\Http\Transformers\Three\PostTransformer
     */
    protected $transformer;

    /**
     * Create a controller instance.
     *
     * @param  PostContract $posts
     * @return void
     */
    public function __construct(PostRepository $post)
    {
        $this->post = $post;
        $this->transformer = new PostTransformer;

        $this->middleware('auth:api');
    }

    /**
     * Update a post(s)'s status when reviewed.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function reviews(Request $request)
    {
        $validatedRequest = $request->validate([
            'post_id' => 'required',
            'status' => 'in:pending,accepted,rejected',
        ]);

        // Only allow an admin to review the post.
        if (token()->role() === 'admin') {
            $post = Post::where('id', $validatedRequest['post_id'])->first();
            $validatedRequest['signup_id'] = $post->signup_id;
            $validatedRequest['northstar_id'] = $post->northstar_id;
            $validatedRequest['old_status'] = $post->status;

            // Append admin's ID to the request for the "reviews" service.
            $validatedRequest['admin_northstar_id'] = auth()->id();
            $reviewedPost = $this->post->reviews($validatedRequest);
            $reviewedPostCode = $this->code($reviewedPost);

            if (isset($reviewedPost)) {
                info('post_reviewed', [
                    'id' => $reviewedPost->id,
                    'admin_northstar_id' => $validatedRequest['admin_northstar_id'],
                    'status' => $reviewedPost->status,
                ]);

                return $this->item($reviewedPost, $reviewedPostCode);
            } else {
                throw (new ModelNotFoundException)->setModel('Post');
            }
        }

        throw new AuthorizationException('You don\'t have the correct role to review this post!');
    }

    /**
     * Determine status code.
     *
     * @param array $reviewed
     *
     * @return int $code
     */
    public function code($reviewed)
    {
        if (empty($reviewed)) {
            return 404;
        } else {
            return 201;
        }
    }
}
