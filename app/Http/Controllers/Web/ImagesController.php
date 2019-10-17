<?php

namespace Rogue\Http\Controllers\Web;

use Storage;
use Rogue\Models\Post;
use Rogue\Services\Fastly;
use Illuminate\Http\Request;
use League\Glide\ServerFactory;
use Rogue\Services\ImageStorage;
use Intervention\Image\Facades\Image;
use Rogue\Http\Controllers\Controller;
use League\Flysystem\Memory\MemoryAdapter;
use League\Flysystem\Filesystem as Flysystem;
use Illuminate\Contracts\Filesystem\Filesystem;
use League\Glide\Responses\LaravelResponseFactory;

class ImagesController extends Controller
{
    /**
     * The Laravel filesystem adapter.
     *
     * @var \Illuminate\Filesystem\FilesystemAdapter
     */
    protected $filesystem;

    /**
     * Create a controller instance.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem, ImageStorage $storage, Fastly $fastly)
    {
        $this->filesystem = $filesystem;
        $this->storage = $storage;
        $this->fastly = $fastly;

        $this->middleware('throttle:60');
    }

    /**
     * Display the specified resource.
     *
     * @param  Post $post
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(string $hash, Request $request)
    {
        $post = Post::fromHash($hash);

        $server = ServerFactory::create([
            'response' => new LaravelResponseFactory($request),
            'cache' => new Flysystem(new MemoryAdapter()),
            'source' => $this->filesystem->getDriver(),
            'base_url' => 'images',
            'defaults' => [
                'or' => 'auto',
                'w' => 400,
                'h' => 400,
                'fit' => 'crop',
            ],
        ]);

        $response = $server->getImageResponse($post->getMediaPath(), $request->all());
        $response->headers->set('Surrogate-Key', 'post-'.$post->id);

        return $response;
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
        $this->validate($request, [
            'rotate' => 'required|int',
        ]);

        $image = Storage::get($post->getMediaPath());

        $angle = (int) -$request->input('rotate');
        $rotatedImage = Image::make($image)->rotate($angle);

        $this->storage->edit($post, $rotatedImage);
        $this->fastly->purge($post);

        return response()->json([
            'url' => $post->getMediaUrl(),
            'original_image_url' => $post->url,
        ]);
    }
}
