<?php

namespace Rogue\Repositories;

use Rogue\Models\Post;
use Rogue\Services\AWS;
use Rogue\Models\Review;
use Rogue\Models\Signup;
use Rogue\Services\Registrar;
use Intervention\Image\Facades\Image;

class PostRepository
{
    /**
     * AWS service class instance.
     *
     * @var \Rogue\Services\AWS
     */
    protected $aws;

    /**
     * The user repository.
     *
     * @var \Rogue\Services\Registrar
     */
    protected $registrar;

    /**
     * Array of properties needed for cropping and rotating.
     *
     * @var array
     */
    protected $cropProperties = ['crop_x', 'crop_y', 'crop_width', 'crop_height', 'crop_rotate'];

    /**
     * Create a PostRepository.
     *
     * @param AWS $aws
     * @param Registrar $registrar
     */
    public function __construct(AWS $aws, Registrar $registrar)
    {
        $this->aws = $aws;
        $this->registrar = $registrar;
    }

    /**
     * Find a post by post_id and return associated signup and tags.
     *
     * @param int $id
     * @return \Rogue\Models\Post
     */
    public function find($id)
    {
        return Post::with('signup', 'tags')->findOrFail($id);
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
        } else {
            $fileUrl = 'default';
        }

        $signup = Signup::find($signupId);

        if (isset($data['quantity'])) {
            $quantityDiff = $data['quantity'] - $signup->quantity;

            if ($quantityDiff < 0) {
                $quantityDiff = $data['quantity'];
            }
        }

        // Create a post.
        $post = new Post([
            'signup_id' => $signup->id,
            'northstar_id' => $data['northstar_id'],
            'campaign_id' => $signup->campaign_id,
            'url' => $fileUrl,
            'caption' => $data['caption'],
            'quantity' => isset($quantityDiff) ? quantityDiff : null,
            'status' => isset($data['status']) ? $data['status'] : 'pending',
            'source' => $data['source'],
            'remote_addr' => $data['remote_addr'],
        ]);

        // @TODO: This can be removed after the migration
        // Let Laravel take care of the timestamps unless they are specified in the request
        if (isset($data['created_at'])) {
            $post->created_at = $data['created_at'];
            $post->updated_at = isset($data['updated_at']) ? $data['updated_at'] : $data['created_at'];
            $post->save(['timestamps' => false]);

            $post->events->first()->created_at = $data['created_at'];
            $post->events->first()->updated_at = $data['created_at'];
            $post->events->first()->save(['timestamps' => false]);
        } else {
            $post->save();
        }


        if (isset($data['quantity'])) {
            $signup->quantity = $signup->getQuantity();
            $signup->save();
        }

        // Edit the image if there is one
        if (isset($data['file'])) {
            $this->crop($data, $post->id);
        }

        return $post;
    }

    /**
     * Update an existing Post and Signup.
     *
     * @param \Rogue\Models\Signup $signup
     * @param array $data
     *
     * @return Signup|Post
     */
    public function update($signup, $data)
    {
        if (array_key_exists('updated_at', $data)) {
            // Should only update why_participated, and timestamps on the signup
            $signupFields = [
                'why_participated' => isset($data['why_participated']) ? $data['why_participated'] : null,
                'updated_at' => $data['updated_at'],
                'created_at' => array_key_exists('created_at', $data) ? $data['created_at'] : null,
            ];

            // Only update if the key is set (is not null).
            $nonNullArrayKeys = array_filter($signupFields);
            $arrayKeysToUpdate = array_keys($nonNullArrayKeys);

            $signup->fill(array_only($data, $arrayKeysToUpdate));
            $signup->save(['timestamps' => false]);

            $event = $signup->events->last();
            $event->created_at = $data['updated_at'];
            $event->updated_at = $data['updated_at'];
            $event->save(['timestamps' => false]);
        } else {
            // Should only update why_participated on the signup
            $signupFields = [
                'why_participated' => isset($data['why_participated']) ? $data['why_participated'] : null,
            ];

            // Only update if the key is set (is not null).
            $nonNullArrayKeys = array_filter($signupFields);
            $arrayKeysToUpdate = array_keys($nonNullArrayKeys);

            $signup->fill(array_only($data, $arrayKeysToUpdate));

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
     * @return Post
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
     * @param object $post
     * @param string $tag
     *
     * @return
     */
    public function tag(Post $post, $tag)
    {
        // If the post already has the tag, soft delete. Otherwise, add the tag to the post.
        if ($post->tagNames()->contains($tag)) {
            $post->untag($tag);
        } else {
            $post->tag($tag);
        }

        // Return the post object including the tags that are related to it.
        return Post::with('signup', 'tags')->findOrFail($post->id);
    }

    /**
     * Crop an image
     *
     * @param  int $signupId
     * @return url|null
     */
    protected function crop($data, $postId)
    {
        $editedImage = Image::make($data['file']);

        // If we have crop values, then use 'em.
        $cropValues = array_only($data, $this->cropProperties);
        if (count($cropValues) > 0) {
            $editedImage = $editedImage
                // Intervention Image rotates images counter-clockwise, but we get values assuming clockwise rotation, so we negate it to rotate clockwise.
                ->rotate(-$cropValues['crop_rotate'])
                ->crop($cropValues['crop_width'], $cropValues['crop_height'], $cropValues['crop_x'], $cropValues['crop_y']);
        } else {
            // Otherwise, try to rotate automatically by EXIF metadata.
            $editedImage = $editedImage->orientate();
        }

        $editedImage = $editedImage->fit(400)
            ->encode('jpg', 75);

        return $this->aws->storeImageData((string) $editedImage, 'edited_' . $postId);
    }
}
