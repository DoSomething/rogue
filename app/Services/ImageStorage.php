<?php

namespace Rogue\Services;

use Log;
use Rogue\Models\Post;
use Intervention\Image\Image;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ImageStorage
{
    /**
     * The base path where images are stored.
     * @param string
     */
    protected $base = 'uploads/reportback-items/';

    /**
     * Write the given File to the storage backend.
     *
     * @param string $signupId
     * @param File $file
     */
    public function put(string $signupId, File $file) {
        $extension = $file->guessExtension();
        $contents = file_get_contents($file->getPathname());

        // Make sure we're only uploading valid image types
        if (! in_array($extension, ['jpeg', 'png', 'gif'])) {
            throw new UnprocessableEntityHttpException('Invalid file type. Upload a JPEG, PNG or GIF.');
        }

        // Create a unique filename for this upload (since we don't know post ID yet).
        $path = $this->base . $signupId . '-' . md5($contents) . '-' . time() . '.' . $extension;

        return $this->write($path, $contents);
    }

    /**
     * Write the given Image to the storage backend.
     *
     * @param string $filename
     * @param Image $image
     *
     */
    public function edit(Post $post, Image $image) {
        if (! $post->url) {
            throw new InvalidArgumentException('Cannot edit an image that does not exist.');
        }

        $path = $post->getMediaPath();
        $contents = (string) $image->encode();

        return $this->write($path, $contents);
    }

    /**
     * Write the image contents to the storage backend.
     *
     * @param string $extension
     * @param string $contents
     *
     * @return string - URL of stored image
     */
    protected function write(string $path, string $contents)
    {
        $success = Storage::put($path, $contents);

        if (! $success) {
            throw new HttpException(500, 'Unable to save image.');
        }

        return Storage::url($path);
    }

    /**
     * Delete a file from s3
     *
     * @param string $path
     * @return bool
     */
    public function delete($path)
    {
        // We need to use the relative url for the request to s3.
        $path = $this->base . basename($path);

        // The delete() method always returns true because it doesn't seem to do anything with
        // any exception that is thrown while trying to delete and just returns true.
        // see: \Illuminate\Filesystem\FilesystemAdapter::delete().
        // So we check if the file exists first and then try to delete it.
        if (Storage::exists($path)) {
            $success = Storage::delete($path);
        } else {
            Log::info('Could not find file when trying to delete.', ['path' => $path]);
            $success = false;
        }

        return $success;
    }
}
