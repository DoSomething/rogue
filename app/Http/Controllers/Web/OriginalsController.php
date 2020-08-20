<?php

namespace Rogue\Http\Controllers\Web;

use Storage;
use Rogue\Models\Post;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Rogue\Http\Controllers\Controller;

class OriginalsController extends Controller
{
    /**
     * Create a controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the specified resource.
     *
     * @param  Post $post
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post, Request $request)
    {
        $this->authorize('viewAll', $post);

        $image = Storage::get($post->getMediaPath());

        return Image::make($image)->response();
    }
}
