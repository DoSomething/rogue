<?php

namespace Rogue\Services;

use Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AWS
{
    /**
     * The base path where images are stored.
     * @param string
     */
    protected $base = 'uploads/reportback-items/';

    /**
     * Store a reportback item (image) in S3.
     *
     * @param UploadedFile $file
     * @param string $filename - Filename to write image to
     *
     * @return string - URL of stored image
     */
    public function storeImage(UploadedFile $file, string $filename)
    {
        $data = file_get_contents($file->getPathname());
        $extension = $file->guessExtension();

        // Make sure we're only uploading valid image types
        if (! in_array($extension, ['jpeg', 'png', 'gif'])) {
            throw new UnprocessableEntityHttpException('Invalid file type. Upload a JPEG, PNG or GIF.');
        }

        // Add a unique timestamp (e.g. uploads/folder/filename-1456498664.jpeg) to
        // uploads to prevent AWS cache giving the user an old upload.
        $path = $this->base . $filename . '-' . md5($data) . '-' . time() . '.' . $extension;

        // Push file to S3.
        $success = Storage::put($path, $data);

        if (! $success) {
            throw new HttpException(500, 'Unable to save image to S3.');
        }

        return Storage::url($path);
    }

    /**
     * Delete a file from s3
     *
     * @param string $path
     * @return bool
     */
    public function deleteImage($path)
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
