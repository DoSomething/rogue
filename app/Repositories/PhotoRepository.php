<?php

namespace Rogue\Repositories;

use Rogue\Models\Post;
use Rogue\Models\Event;
use Rogue\Models\Photo;
use Rogue\Services\AWS;
use Rogue\Services\Registrar;

class PhotoRepository
{
    /**
     * AWS service class instance.
     *
     * @var \Rogue\Services\AWS
     */
    protected $AWS;

    /**
     * Array of properties needed for cropping and rotating.
     *
     * @var array
     */
    protected $cropProperties = ['crop_x', 'crop_y', 'crop_width', 'crop_height', 'crop_rotate'];

    /**
     * Constructor
     */
    public function __construct(AWS $aws, Registrar $registrar)
    {
        $this->aws = $aws;
        $this->registrar = $registrar;
    }

    /**
     * Create an event.
     *
     * @param  array $data
     * @param  int $signupId
     * @return \Rogue\Models\Photo|null
     */
    public function create(array $data, $signupId)
    {
        $postEvent = Event::create($data);

        $fileUrl = $this->aws->storeImage($data['file'], $signupId);

        $editedImage = $this->crop($data, $signupId);

        $photo = Photo::create([
            'file_url' => $fileUrl,
            'edited_file_url' => $editedImage,
            'caption' => $data['caption'],
            'status' => isset($data['status']) ? $data['status'] : 'pending',
            'source' => $data['source'],
            'remote_addr' => $data['remote_addr'],
        ]);

        // Create the Post and associate the Photo with it.
        // @Note -- Having some issue using the `create` method here. I think
        // becase the Posts table doesn't have an `id` key, but I can work that out.

        $post = new Post([
            'event_id' => $postEvent->id,
            'signup_id' => $signupId,
            'northstar_id' => $data['northstar_id'],
        ]);

        $post->content()->associate($photo);
        $post->save();

        // $this->registrar->find($data['northstar_id']);

        return $post;
    }

    /**
     * Update an existing photo post and signup.
     *
     * @param \Rogue\Models\Photo $signup
     * @param array $data
     *
     * @return \Rogue\Models\Photo
     */
    public function update($signup, $data) {
        // If there is a file, create a new photo post.
        if (isset($data['file'])) {
            return $this->create($data, $signup->id);
        } elseif (array_key_exists('file', $data)) {
            return $this->create($data, $signup->id);
        // Otherwise, only update the signup's quantity and/or why_participated data.
        } else {
            Event::create($data);

            $data['quantity_pending'] = $data['quantity'];

            $signup->fill(array_only($data, ['quantity_pending', 'why_participated']));

            $signup->save();

            return $signup;
        }
    }

    /**
     * Crop an image
     *
     * @param  int $signupId
     * @return url|null
     */
    protected function crop($data, $signupId)
    {
        $cropValues = array_only($data, $this->cropProperties);

        if (count($cropValues) > 0) {
            $editedImage = edit_image($data['file'], $cropValues);

            return $this->aws->storeImage($editedImage, 'edited_' . $signupId);
        }

        return null;
    }
}
