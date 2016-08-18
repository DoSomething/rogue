<?php

namespace Rogue\Services;

use Storage;

class AWS
{
    /**
     * Store a reportback item (image) in S3.
     *
     * @param File $file
     */
    public function storeReportbackItem($file, $file_id)
    {
        $extension = $file->guessExtension();

        // Generate filename here.
        $filename = 'uploads/' . env('S3_BUCKET') . '/' . $file_id . '.' . $extension;

        // Push file to S3.
        // Storage::disk('s3')->put($filename, file_get_contents($file));

        // Return 's3.amazon.com/uploads/{bucket}/{file_id}.{extension}'
        return $filename;
    }
}
