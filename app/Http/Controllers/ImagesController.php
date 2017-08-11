<?php

namespace Rogue\Http\Controllers;

use League\Glide\Responses\LaravelResponseFactory;
use Illuminate\Contracts\Filesystem\Filesystem;
use League\Glide\ServerFactory;
use Illuminate\Http\Request;
use Rogue\Models\Post;

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
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
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
        // Create the Glide server.
        $server = ServerFactory::create([
            'response' => new LaravelResponseFactory($request),
            'source' => $this->filesystem->getDriver(),
            'cache' => $this->filesystem->getDriver(),
            'cache_path_prefix' => '.cache',
            'base_url' => 'images',
            'defaults' => [
                'w' => 400,
                'h' => 400,
                'fit' => 'crop',
            ]
        ]);

        return $server->getImageResponse($post->getMediaPath(), $request->all());
    }
}
