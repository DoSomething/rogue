<?php

namespace Rogue\Http\Controllers;

use Storage;
use Rogue\Models\Post;
use Rogue\Services\Fastly;
use Illuminate\Http\Request;
use Rogue\Services\ImageStorage;
use Rogue\Http\Transformers\PostTransformer;

class RotationController extends Controller
{
    /**
     * The Fastly API.
     *
     * @var Fastly
     */
    protected $fastly;

    /**
     * The image storage backend.
     *
     * @var ImageStorage
     */
    protected $storage;

    /**
     * @var PostTransformer;
     */
    protected $transformer;

    /**
     * Create a controller instance.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Fastly $fastly, ImageStorage $storage, PostTransformer $transformer)
    {
        $this->fastly = $fastly;
        $this->storage = $storage;
        $this->transformer = $transformer;

        $this->middleware('auth', ['only' => 'update']);
        $this->middleware('role:staff,admin', ['only' => 'update']);
    }

    /**
     * Edits and overwrites an image based on given request parameters.
     *
     * @param  Post $post
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Post $post, Request $request)
    {
        $validatedInput = $this->validate($request, [
            'degrees' => 'required|int',
        ]);

        $image = $this->storage->get($post);

        // We use clockwise degrees, but Intervention rotates images
        // counter-clockwise <http://image.intervention.io/api/rotate>
        $rotatedImage = $image->rotate(-1 * $validatedInput['degrees']);

        // Overwrite with edited image & purge cache:
        $this->storage->edit($post, $rotatedImage);
        $this->fastly->purge($post);

        return $this->item($post);
    }
}
