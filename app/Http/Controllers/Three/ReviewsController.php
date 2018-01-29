<?php

namespace Rogue\Http\Controllers\Three;

use Rogue\Models\Post;
use Illuminate\Http\Request;
use Rogue\Repositories\Three\PostRepository;
use Rogue\Http\Controllers\Api\ApiController;
use Illuminate\Auth\Access\AuthorizationException;
use Rogue\Http\Transformers\Three\PostTransformer;

class ReviewsController extends ApiController
{
    /**
     * The post repository instance.
     *
     * @var Rogue\Repositories\Three\PostRepository
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
        $request->validate([
            'post_id' => 'required:exists:posts',
            'status' => 'in:pending,accepted,rejected',
        ]);

        // Only allow an admin to review the post.
        if (token()->role() === 'admin') {
            $review = $request->all();
            $post = Post::where('id', $request['post_id'])->first();

            // Append admin's ID to the request for the "reviews" service.
            $reviewedPost = $this->post->reviews($post, $request['status'], isset($request['comment']) ? $request['comment'] : null);

            $reviewedPostCode = $this->code($reviewedPost);

            info('post_reviewed', [
                'id' => $reviewedPost->id,
                'admin_northstar_id' => $reviewedPost->admin_northstar_id,
                'status' => $reviewedPost->status,
            ]);

            return $this->item($reviewedPost, $reviewedPostCode);
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
