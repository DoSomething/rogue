<?php

namespace Rogue\Repositories;

use Rogue\Models\Post;
use Rogue\Services\AWS;
// use Rogue\Models\Review;
use Rogue\Services\Registrar;
use Intervention\Image\Facades\Image;

class PostRepository
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
     * Create a Post.
     *
     * @param  array $data
     * @param  int $signupId
     * @return \Rogue\Models\Post|null
     */
    public function create(array $data, $signupId)
    {
        if (isset($data['file'])) {
            // Auto-orient the photo by default based on exif data.
            $img = Image::make($data['file'])->orientate();

            $fileUrl = $this->aws->storeImage((string) $img->encode('data-url'), $signupId);

            $editedImage = $this->crop($data, $signupId);
        } else {
            $fileUrl = 'default';
        }

        // Create a post.
        $post = new Post([
            'signup_id' => $signupId,
            'northstar_id' => $data['northstar_id'],
            'url' => $fileUrl,
            'caption' => $data['caption'],
            'status' => isset($data['status']) ? $data['status'] : 'pending',
            'source' => $data['source'],
            'remote_addr' => $data['remote_addr'],
        ]);

        // @TODO: This can be removed after the migration
        // Let Laravel take care of the timestamps unless they are specified in the request
        if (isset($data['created_at'])) {
            $post->created_at = $data['created_at'];
            $post->updated_at = $data['created_at'];
            $post->save(['timestamps' => false]);

            $post->events->first()->created_at = $data['created_at'];
            $post->events->first()->updated_at = $data['created_at'];
            $post->events->first()->save(['timestamps' => false]);
        } else {
            $post->save();
        }

        return $post;
    }

    /**
     * Update an existing Post and Signup.
     *
     * @param \Rogue\Models\Post $signup
     * @param array $data
     *
     * @return \Rogue\Models\Post
     */
    public function update($signup, $data)
    {
        $signup->fill(array_only($data, ['quantity_pending', 'why_participated']));

        // Triggers model event that logs the updated signup in the events table.
        $signup->save();

        // If there is a file, create a new post.
        if (! empty($data['file'])) {
            return $this->create($data, $signup->id);
        }

        return $signup;
    }

    /**
     * Updates a photo(s)'s status after being reviewed.
     * @todo - update with new logic once photos table is removed
     * and everything lives on the post.
     *
     * @param array $data
     *
     * @return
     */
    // public function reviews($data)
    // {
    //     $reviewedPhotos = [];

    //     if (isset($data['rogue_event_id']) && ! empty($data['rogue_event_id'])) {
    //         $post = Post::where(['event_id' => $data['rogue_event_id']])->first();
    //         $photo = Photo::where(['id' => $post->postable_id])->first();

    //         if ($data['status'] && ! empty($data['status'])) {
    //             // @TODO: update to add more details in the event e.g. admin who reviewed, admin's northstar id, etc.
    //             $data['submission_type'] = 'admin';

    //             // Create the Event.
    //             $event = Event::create([
    //                 'signup_id' => $post->signup_id,
    //                 'northstar_id' => $post->northstar_id,
    //                 'event_type' => $data['event_type'],
    //                 'submission_type' => $data['submission_type'],
    //                 // When we start tracking when admins update the below, we'll need to update this endpoint and comment these in.
    //                 // 'quantity' => ,
    //                 // 'quantity_pending' => ,
    //                 // 'why_participated' => ,
    //                 // 'caption' => ,
    //                 'status' => $data['status'],
    //                 // 'source' => ,
    //                 // 'remote_addr' => ,
    //                 // 'reason' => ,
    //             ]);

    //             // Create the Review.
    //             Review::create([
    //                 'event_id' => $event->id,
    //                 'signup_id' => $post->signup_id,
    //                 'northstar_id' => $post->northstar_id,
    //                 'admin_northstar_id' => $data['reviewer'],
    //                 'status' => $data['status'],
    //                 'old_status' => $photo->status,
    //                 'comment' => isset($data['comment']) ? $data['comment'] : null,
    //                 'created_at' => $event->created_at,
    //                 'updated_at' => $event->updated_at,
    //                 'postable_id' => $post->postable_id,
    //                 'postable_type' => $post->postable_type,
    //             ]);

    //             $photo->status = $data['status'];
    //             $photo->save();
    //         } else {
    //             return null;
    //         }
    //     } else {
    //         return null;
    //     }

    //     return $photo;
    // }

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
            $editedImage = edit_image($img, $cropValues);

            return $this->aws->storeImage($editedImage, 'edited_' . $signupId);
        }

        return null;
    }
}
