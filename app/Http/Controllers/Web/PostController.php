<?php

namespace Rogue\Http\Controllers\Web;

use Rogue\Services\Fastly;
use Rogue\Services\PostService;
use Rogue\Repositories\SignupRepository;
use Rogue\Http\Transformers\Legacy\Two\PostTransformer;
use Rogue\Http\Controllers\Traits\PostRequests;
use Rogue\Http\Controllers\Controller;

class PostController extends Controller
{
    use PostRequests;

    /**
     * The Fastly service instance
     *
     * @var Rogue\Services\Fastly
     */
    protected $fastly;

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
     * @var Rogue\Http\Transformers\Legacy\Two\PostTransformer;
     */
    protected $transformer;

    /**
     * Create a controller instance.
     *
     * @param  PostContract  $posts
     * @return void
     */
    public function __construct(PostService $posts, SignupRepository $signups, Fastly $fastly)
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');

        $this->fastly = $fastly;
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
            $this->fastly->purgeKey('post-'.$postId);

            return response()->json(['code' => 200, 'message' => 'Post deleted.']);
        } else {
            return response()->json(['code' => 500, 'message' => 'There was an error deleting the post']);
        }
    }
}
