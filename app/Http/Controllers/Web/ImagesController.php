<?php

namespace Rogue\Http\Controllers\Web;

use Rogue\Models\Post;
use Illuminate\Http\Request;
use League\Glide\ServerFactory;
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
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

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

        // We want these to be cached in Fastly, but not in the user's browser, since
        // otherwise rotations/deletions won't take effect until the user clears their cache:
        $response->headers->set('Cache-Control', 's-max-age=31536000, public');
        $response->headers->remove('Expires');

        // We can then use the 'Surrogate-Key' to easily clear this from
        // Fastly's cache if the post's image is modified later on:
        $response->headers->set('Surrogate-Key', 'post-'.$post->id);

        return $response;
    }
}
