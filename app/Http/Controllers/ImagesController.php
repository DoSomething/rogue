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
     * @TODO - Currently rotates and overwrites both the original image and
     * processed, edited image. We will not need to work with the edited image
     * when Glide processing is turned on, so we should remove this logic when
     * that is live on prod.
     *
     * @param  $id
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $post = Post::findOrFail($id);

        // Get the filename of the original image.
        $originalImage = $post->url;
        $originalFilename = $this->getFilenameFromUrl($originalImage);

        // Get the url of the processed, edited image.
        $editedImage = $post->getMediaUrl();

        // Only supports rotation, right now.
        if ($request->input('rotate')) {
            $value = (int) -$request->input('rotate');

            $originalImage = Image::make($originalImage)->rotate($value)->encode('jpg', 75);
            $editedImage = Image::make($editedImage)->rotate($value)->encode('jpg', 75);
        }

        $originalImage = $this->aws->storeImageData((string) $originalImage, $originalFilename);
        $editedImage = $this->aws->storeImageData((string) $editedImage, 'edited_' . $post->id);

        return response()->json([
            'url' => $editedImage,
            'original_image_url' => $originalImage,
        ]);
    }

    /**
     * Returns an image file name without the extension based on a given url
     *
     * @param  string $url
     * @return string $filename
     */
    private function getFilenameFromUrl($url)
    {
        $path = explode('/', $url);

        if ($path) {
            $filename = end($path);
            $filename = pathinfo($filename, PATHINFO_FILENAME);

            return $filename;
        }

        return null;
    }
}
