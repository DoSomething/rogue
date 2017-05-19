<?php

namespace Rogue\Http\Controllers;

use Illuminate\Http\Request;
use Rogue\Services\PostService;

class PostController extends Controller
{
    /**
     * The post service instance.
     *
     * @var Rogue\Services\PostService
     */
    protected $posts;

    /**
     * Create a controller instance.
     *
     * @param  PostContract  $posts
     * @return void
     */
    public function __construct(PostService $posts)
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');

        $this->posts = $posts;
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
