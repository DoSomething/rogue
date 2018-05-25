<?php

namespace Rogue\Services;

use Log;
use finfo;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AWS
{
    /**
     * Store a reportback item (image) in S3.
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile|string $file
     *  File object, or a base-64 encoded data URI
     * @param string $filename - Filename to write image to
     *
     * @return string - URL of stored image
     */
    public function storeImage($file, $filename)
    {
        if (is_string($file)) {
            $data = $this->base64StringToDataString($file);
        } else {
            $data = file_get_contents($file->getPathname());
        }

        $extension = $this->guessExtension($data);

        // Make sure we're only uploading valid image types
        if (! in_array($extension, ['jpeg', 'png', 'gif'])) {
            throw new UnprocessableEntityHttpException('Invalid file type. Upload a JPEG, PNG or GIF.');
        }

        // Add a unique timestamp (e.g. uploads/folder/filename-1456498664.jpeg) to
        // uploads to prevent AWS cache giving the user an old upload.
        $path = 'uploads/reportback-items' . '/' . $filename . '-' . md5($data) . '-' . time() . '.' . $extension;

        // Push file to S3.
        $success = Storage::put($path, $data);

        if (! $success) {
            throw new HttpException(500, 'Unable to save image to S3.');
        }

        return Storage::url($path);
    }

    /**
     * Store a reportback item (image) in S3.
     *
     * @param string $data - File data
     *
     * @return string - URL of stored image
     */
    public function storeImageData($data, $filename)
    {
        $extension = $this->guessExtension($data);

        // Make sure we're only uploading valid image types
        if (! in_array($extension, ['jpeg', 'png', 'gif'])) {
            throw new UnprocessableEntityHttpException('Invalid file type. Upload a JPEG, PNG or GIF.');
        }

        $path = 'uploads/reportback-items/' . $filename . '.' . $extension;

        // Push file to S3.
        $success = Storage::put($path, $data);

        if (! $success) {
            throw new HttpException(500, 'Unable to save image to S3.');
        }

        return Storage::url($path);
    }

    /**
     * Guess the extension from a data buffer string.
     * @param string $data - Data buffer string
     * @return string - file extension
     */
    protected function guessExtension($data)
    {
        $f = new finfo();
        $mimeType = $f->buffer($data, FILEINFO_MIME_TYPE);
        $guesser = ExtensionGuesser::getInstance();

        return $guesser->guess($mimeType);
    }

    /**
     * Decode Base-64 encoded string into a raw data buffer string.
     * @param $string - Base-64 encoded string
     * @return string - raw data
     */
    protected function base64StringToDataString($string)
    {
        // Trim the mime-type (e.g. `data:image/png;base64,`) from the string
        $file = last(explode(',', $string));

        return base64_decode($file);
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
        $path = basename($path);
        $path = 'uploads/reportback-items' . '/' . $path;

        // The delete() method always returns true because it doesn't seem to do anything with
        // any exception that is thrown while trying to delete and just returns true.
        // see: \Illuminate\Filesystem\FilesystemAdapter::delete().
        // So we check if the file exists first and then try to delete it.
        Log::info('sending to exists ' . $path);
        if (Storage::exists($path)) {
            $success = Storage::delete($path);
        } else {
            Log::info('Could not find file when trying to delete.', ['path' => $path]);
            $success = false;
        }

        return $success;
    }
}
