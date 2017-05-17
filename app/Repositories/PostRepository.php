<?php

namespace Rogue\Repositories;

use Rogue\Models\Post;
use Rogue\Services\AWS;
use Rogue\Models\Review;
use Rogue\Models\Event;
use Conner\Tagging\Model\Tagged;
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
            $image = Image::make($data['file'])->orientate();

            $fileUrl = $this->aws->storeImage((string) $image->encode('data-url'), $signupId);

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
        if (array_key_exists('updated_at', $data)) {
            $signup->fill(array_only($data, ['quantity', 'quantity_pending', 'why_participated', 'updated_at']));

            $signup->save(['timestamps' => false]);

            $event = $signup->events->last();
            $event->created_at = $data['updated_at'];
            $event->updated_at = $data['updated_at'];
            $event->save(['timestamps' => false]);
        } else {
            $signup->fill(array_only($data, ['quantity', 'quantity_pending', 'why_participated']));

            // Triggers model event that logs the updated signup in the events table.
            $signup->save();
        }

        // If there is a file, create a new post.
        if (array_key_exists('file', $data)) {
            return $this->create($data, $signup->id);
        }

        return $signup;
    }

    /**
     * Delete a post and remove the file from s3.
     *
     * @param int $postId
     * @return $post;
     */
    public function destroy($postId)
    {
        $post = Post::findOrFail($postId);

        // Delete the image file from AWS.
        $this->aws->deleteImage($post->url);

        // Set the url of the post to null.
        $post->url = null;
        $post->save();

        // Soft delete the post.
        $post->delete();

        return $post->trashed();
    }

    /**
     * Updates a post's status after being reviewed.
     *
     * @param array $data
     *
     * @return
     */
    public function reviews($data)
    {
        $post = Post::where(['id' => $data['post_id']])->first();

        // Create the Review.
        $review = Review::create([
            'signup_id' => $post->signup_id,
            'northstar_id' => $post->northstar_id,
            'admin_northstar_id' => $data['admin_northstar_id'],
            'status' => $data['status'],
            'old_status' => $post->status,
            'comment' => isset($data['comment']) ? $data['comment'] : null,
            'post_id' => $post->id,
        ]);

        // Update the status on the Post.
        $post->status = $data['status'];
        $post->save();

        return $post;
    }

    /**
     * Updates a post's tags when added or deleted.
     *
     * @param array $data
     *
     * @return
     */
    public function tag($data)
    {
        $post = Post::findOrFail($data['post_id']);

        // Check if the post already has the tag.
        // If so, soft delete. Otherwise, add the tag to the post.
        if (in_array($data['tag_name'], $post->tagNames())) {
            $post->untag($data['tag_name']);

            // Create an event when the tag is deleted.
            if (!in_array($data['tag_name'], $post->tagNames())) {
                $post->tag_name = $data['tag_name'];
                $post->admin_northstar_id = $data['admin_northstar_id'];
                $post->action = 'delete';

                $event = Event::create([
                    'eventable_id' => $post->id,
                    'eventable_type' => 'Conner\Tagging\Model\Tagged',
                    'content' => $post->toJson(),
                ]);
            }
        } else {
            $post->tag($data['tag_name']);

            // Create an event when the tag is saved.
            if (in_array($data['tag_name'], $post->tagNames())) {
                $post->tag_name = $data['tag_name'];
                $post->admin_northstar_id = $data['admin_northstar_id'];
                $post->action = 'create';

                Event::create([
                    'eventable_id' => $post->id,
                    'eventable_type' => 'Conner\Tagging\Model\Tagged',
                    'content' => $post->toJson(),
                ]);
            }
        }

        return $post;
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
