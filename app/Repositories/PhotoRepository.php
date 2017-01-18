<?php

namespace Rogue\Repositories;

use Rogue\Models\Post;
use Rogue\Models\Event;
use Rogue\Models\Photo;
use Rogue\Services\AWS;
use Rogue\Models\Signup;
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

        if (array_key_exists('campaign_id', $data)) {
            $fileUrl = $this->aws->storeImage($data['file'], $data['campaign_id']);
        } else {
            $fileUrl = $this->aws->storeImage($data['file'], $signupId);
        }

        $editedImage = $this->crop($data);

        $photo = Photo::create([
            'northstar_id' => $data['northstar_id'],
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
        ]);

        $post->content()->associate($photo);
        $post->save();

        // $this->registrar->find($data['northstar_id']);

        return $post;
    }

    /**
     * Crop an image
     *
     * @param  int $signupId
     * @return url|null
     */
    protected function crop($data)
    {
        $cropValues = array_only($data, $this->cropProperties);

        if (count($cropValues) > 0) {
            $editedImage = edit_image($data['file'], $cropValues);

            return $this->aws->storeImage($editedImage, 'edited_' . $data['campaign_id']);
        }

        return null;
    }
}
