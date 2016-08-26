<?php

namespace Rogue\Services;

use finfo;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AWS
{
    /**
     * The Amazon S3 file system.
     * @see https://laravel.com/docs/5.2/filesystem
     * @var Filesystem
     */
    protected $filesystem;

    public function __construct(FilesystemManager $filesystem)
    {
        $this->filesystem = $filesystem->disk('s3');
    }

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
        if (! in_array($extension, ['jpeg', 'png'])) {
            throw new UnprocessableEntityHttpException('Invalid file type. Upload a JPEG or PNG.');
        }

        // Add a unique timestamp (e.g. uploads/folder/filename-1456498664.jpeg) to
        // uploads to prevent AWS cache giving the user an old upload.
        $path = '/uploads/reportback-items' . '/' . $filename . '-' . time() . '.' . $extension;

        // Push file to S3.
        $success = $this->filesystem->put($path, $data);

        if (! $success) {
            throw new HttpException(500, 'Unable to save image to S3.');
        }

        return config('filesystems.disks.s3.public_url') . $path;
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
}
