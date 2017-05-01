<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Post;
use Rogue\Models\Signup;
use Illuminate\Http\Request;
use Rogue\Services\PostService;
use Rogue\Http\Requests\PostRequest;
use Rogue\Repositories\SignupRepository;
use Rogue\Http\Transformers\PostTransformer;

class PostsController extends ApiController
{
    /**
     * The post service instance.
     *
     * @var Rogue\Services\PostService
     */
    protected $posts;

    /**
     * The signup repository instance.
     *
     * @var Rogue\Repositories\SignupRepository
     */
    protected $signups;

    /**
     * @var \League\Fractal\TransformerAbstract;
     */
    protected $transformer;

    /**
     * Create a controller instance.
     *
     * @param  PostContract  $posts
     * @return void
     */
    public function __construct(PostService $posts, SignupRepository $signups)
    {
        $this->middleware('api');

        $this->posts = $posts;
        $this->signups = $signups;

        $this->transformer = new PostTransformer;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // @TODO - Validate post request.
    public function store(PostRequest $request)
    {
        $transactionId = incrementTransactionId($request);

        $signup = $this->signups->get($request['northstar_id'], $request['campaign_id'], $request['campaign_run_id']);

        $updating = ! is_null($signup);

        // @TODO - should we eventually throw an error if a signup doesn't exist before a post is created? I create one here because we haven't implemented sending signups to rogue yet, so it will have to create a signup record for all posts.
        if (! $updating) {
            $signup = $this->signups->create($request->all());

            // Send the data to the PostService class which will handle determining
            // which type of post we are dealing with and which repostitory to use to actually create the post.
            $post = $this->posts->create($request->all(), $signup->id, $transactionId);

            $code = 200;

            return $this->item($post);
        } else {
            $post = $this->posts->update($signup, $request->all(), $transactionId);

            $code = 201;

            if (isset($request['file'])) {
                return $this->item($post);
            } else {
                return $signup;
            }
        }
    }

    /**
     * Delete a resource in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($postId)
    {
        $postDeleted = $this->posts->destroy($postId);

        if ($postDeleted) {
            return response()->json(['code' => 200, 'message' => 'Post deleted.']);
        } else {
            return response()->json(['code' => 500, 'message' => 'There was an error deleting the post']);
        }
    }
}
