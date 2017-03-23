<?php

namespace Rogue\Repositories;

use Rogue\Models\Post;
use Rogue\Models\Event;
use Rogue\Models\Photo;
use Rogue\Services\AWS;
use Rogue\Models\Review;
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
     * Create a Photo.
     *
     * @param  array $data
     * @param  int $signupId
     * @return \Rogue\Models\Photo|null
     */
    public function create(array $data, $signupId)
    {
        if (isset($data['file'])) {
            $fileUrl = $this->aws->storeImage($data['file'], $signupId);

            $editedImage = $this->crop($data, $signupId);
        } else {
            $fileUrl = 'default';
        }

        $photo = Photo::create([
            'file_url' => $fileUrl,
            'edited_file_url' => isset($editedImage) ? $editedImage : null,
            'caption' => $data['caption'],
        ]);

        // @TODO: This if can be removed after the migration
        // Let Laravel take care of the timestamps unless they are specified in the request
        if (isset($data['created_at'])) {
            $photo->created_at = $data['created_at'];
            $photo->updated_at = $data['created_at'];

            $photo->save(['timestamps' => false]);
        }

        // Create the Post and associate the Photo with it.
        // @Note -- Having some issue using the `create` method here. I think
        // becase the Posts table doesn't have an `id` key, but I can work that out.
        $post = new Post([
            'signup_id' => $signupId,
            'northstar_id' => $data['northstar_id'],
            'status' => isset($data['status']) ? $data['status'] : 'pending',
            'source' => $data['source'],
            'remote_addr' => $data['remote_addr'],
        ]);

        // @TODO: After the migration, only keep the code in the else
        // Let Laravel take care of the timestamps unless they are specified in the request
        // Post and Photo would have the same timestamps
        if (isset($data['created_at'])) {
            $post->content()->associate($photo);

            $post->created_at = $data['created_at'];
            $post->updated_at = $data['created_at'];

            $post->save(['timestamps' => false]);
        } else {
            // @TODO: keep this after the migration
            $post->content()->associate($photo);
            $post->save();
        }

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
    public function update($signup, $data)
    {
        $signup->fill(array_only($data, ['quantity_pending', 'why_participated']));

        // Triggers model event that logs the updated signup in the events table.
        $signup->save();

        // If there is a file, create a new photo post.
        if (! empty($data['file'])) {
            return $this->create($data, $signup->id);
        }

        return $signup;
    }

    /**
     * Updates a photo(s)'s status after being reviewed.
     *
     * @param array $data
     *
     * @return
     */
    public function reviews($data)
    {
        $reviewedPhotos = [];

        if (isset($data['rogue_event_id']) && ! empty($data['rogue_event_id'])) {
            $post = Post::where(['event_id' => $data['rogue_event_id']])->first();
            $photo = Photo::where(['id' => $post->postable_id])->first();

            if ($data['status'] && ! empty($data['status'])) {
                // @TODO: update to add more details in the event e.g. admin who reviewed, admin's northstar id, etc.
                $data['submission_type'] = 'admin';

                // Create the Event.
                $event = Event::create([
                    'signup_id' => $post->signup_id,
                    'northstar_id' => $post->northstar_id,
                    'event_type' => $data['event_type'],
                    'submission_type' => $data['submission_type'],
                    // When we start tracking when admins update the below, we'll need to update this endpoint and comment these in.
                    // 'quantity' => ,
                    // 'quantity_pending' => ,
                    // 'why_participated' => ,
                    // 'caption' => ,
                    'status' => $data['status'],
                    // 'source' => ,
                    // 'remote_addr' => ,
                    // 'reason' => ,
                ]);

                // Create the Review.
                Review::create([
                    'event_id' => $event->id,
                    'signup_id' => $post->signup_id,
                    'northstar_id' => $post->northstar_id,
                    'admin_northstar_id' => $data['reviewer'],
                    'status' => $data['status'],
                    'old_status' => $photo->status,
                    'comment' => isset($data['comment']) ? $data['comment'] : null,
                    'created_at' => $event->created_at,
                    'updated_at' => $event->updated_at,
                    'postable_id' => $post->postable_id,
                    'postable_type' => $post->postable_type,
                ]);

                $photo->status = $data['status'];
                $photo->save();
            } else {
                return null;
            }
        } else {
            return null;
        }

        return $photo;
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
