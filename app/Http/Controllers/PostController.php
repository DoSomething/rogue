<?php

namespace Rogue\Http\Controllers;

use Rogue\Services\PostService;
use Rogue\Repositories\SignupRepository;
use Rogue\Http\Transformers\PostTransformer;
use Rogue\Http\Controllers\Traits\PostRequests;

class PostController extends Controller
{
    use PostRequests;

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
        $this->middleware('auth');
        $this->middleware('role:admin,staff');

        $this->posts = $posts;
        $this->signups = $signups;

        $this->transformer = new PostTransformer;
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
