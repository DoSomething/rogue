<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Post;
use Rogue\Services\AWS;
use Illuminate\Http\Request;
use League\Glide\ServerFactory;
use Intervention\Image\Facades\Image;
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
    public function __construct(Filesystem $filesystem, AWS $aws)
    {
        $this->filesystem = $filesystem;
        $this->aws = $aws;
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
        if (! config('features.glide')) {
            abort(501, 'Glide image URLs are not enabled in this environment.');
        }

        // Create the Glide server.
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

        return $server->getImageResponse($post->getMediaPath(), $request->all());
    }

    /**
     * Edits and overwrites an image based on given request parameters.
     *
     * @param  $id
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $post = Post::findOrFail($id);
        $originalImage = $post->getMediaUrl();

        // Only supports rotation, right now.
        if ($request->input('rotate')) {
            $value = (int) -$request->input('rotate');

            $originalImage =  Image::make($originalImage)->rotate($value)->encode('jpg', 75);
        }

        $image = $this->aws->storeImageData((string) $originalImage, 'edited_' . $post->id);

        return response()->json([
            'url' => $image,
        ]);
    }
}
