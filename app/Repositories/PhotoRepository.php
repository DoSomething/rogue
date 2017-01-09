<?php

namespace Rogue\Repositories;

use Rogue\Models\Event;
use Rogue\Models\Photo;
use Rogue\Models\Post;
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

        $fileUrl = $this->aws->storeImage($data['file'], $data['campaign_id']);

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

        // move in to Post service class as a help?
        // or a PostRepo that these other specific repos extend?
        $post = new Post([
            'event_id' => $postEvent->id,
            'signup_id' => $signupId,
        ]);

        $post->postData()->associate($photo);
        $post->save();

        // $this->registrar->find($data['northstar_id']);

        return $post;
    }

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
