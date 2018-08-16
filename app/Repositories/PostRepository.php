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
     * @param  string $authenticatedUserRole
     *
     * @return \Rogue\Models\Post|null
     */
    public function create(array $data, $signupId, $authenticatedUserRole = null)
    {
        if (isset($data['file'])) {
            // Auto-orient the photo by default based on exif data.
            $image = Image::make($data['file']);

            $fileUrl = $this->aws->storeImage((string) $image->encode('data-url'), $signupId);
        } else {
            $fileUrl = null;
        }

        $signup = Signup::find($signupId);

        // Create a post.
        $post = new Post([
            'signup_id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'quantity' => isset($data['quantity']) ? $data['quantity'] : null,
            'type' => isset($data['type']) ? $data['type'] : 'photo',
            'action' => isset($data['action']) ? $data['action'] : null,
            'url' => $fileUrl,
            'text' => isset($data['text']) ? $data['text'] : null,
            'source' => token()->client(),
            'source_details' => isset($data['source_details']) ? $data['source_details'] : null,
            'details' => isset($data['details']) ? $data['details'] : null,
        ]);

        // If this is a share-social type post, auto-accept.
        $post->status = $post->type === 'share-social' ? 'accepted' : 'pending';

        $isAdmin = auth()->user() && auth()->user()->role === 'admin';
        $hasAdminScope = in_array('admin', token()->scopes());

        // Admin users may provide a source, status, and created_at when uploading a post.
        if ($isAdmin || $hasAdminScope) {
            $post->status = isset($data['status']) ? $data['status'] : 'pending';
            $post->source = isset($data['source']) ? $data['source'] : token()->client();

            // If there is a created_at property, fill this in (e.g. if created_at is sent when creating a record with the importer app).
            if (isset($data['created_at'])) {
                $post->created_at = strtotime($data['created_at']);
            }
        }

        $post->save();

        // Edit the image if there is one
        if (isset($data['file'])) {
            $this->crop($data, $post->id);
        }

        // Update the signup's total quantity and why_participated if sent.
        if (isset($data['why_participated'])) {
            $signup->why_participated = $data['why_participated'];
        }

        $signup->quantity = $signup->posts->sum('quantity');
        $signup->save();

        return $post;
    }

    /**
     * Update an existing Post.
     *
     * @param \Rogue\Models\Post $post
     * @param array $data
     *
     * @return Post
     */
    public function update($post, $data)
    {
        $post->update($data);

        // If the quantity was updated, update the total quantity on the signup.
        if (isset($data['quantity'])) {
            $signup = $post->signup;
            $signup->quantity = $signup->posts->sum('quantity');
            $signup->save();
        }

        return $post;
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
     * @param array Post $post
     * @param string $status
     * @param string $comment (optional)
     *
     * @return Post
     */
    public function reviews(Post $post, $status, $comment = null, $admin = null)
    {
        // Create the Review.
        $review = Review::create([
            'signup_id' => $post->signup_id,
            'northstar_id' => $post->northstar_id,
            'admin_northstar_id' => $admin ? $admin : auth()->id(),
            'status' => $status,
            'old_status' => $post->status,
            'comment' => $comment,
            'post_id' => $post->id,
        ]);

        // Update the status on the Post.
        $post->status = $status;
        $post->save();

        return $post;
    }

    /**
     * Updates a post's tags when added.
     *
     * @param object $post
     * @param string $tag
     *
     * @return
     */
    public function tag(Post $post, $tag)
    {
        // Check to see if the post already has this tag.
        // If so, no need to add again.
        if (! $post->tagNames()->contains($tag)) {
            $post->tag($tag);
        }

        // Return the post object including the tags that are related to it.
        return Post::with('signup', 'tags')->findOrFail($post->id);
    }

    /**
     * Updates a post's tags when deleted.
     *
     * @param object $post
     * @param string $tag
     *
     * @return
     */
    public function untag(Post $post, $tag)
    {
        // If the post already has the tag, delete. Otherwise, don't do anything.
        if ($post->tagNames()->contains($tag)) {
            $post->untag($tag);
        }

        // Return the post object including the tags that are related to it.
        return Post::with('signup', 'tags')->findOrFail($post->id);
    }

    /**
     * Crop an image
     *
     * @TODO - remove when glide is permanent.
     *
     * @param  int $signupId
     * @return url|null
     */
    protected function crop($data, $postId)
    {
        $editedImage = Image::make($data['file']);

        // use default crop (400x400)
        $editedImage = $editedImage->fit(400, 400)->encode('jpg', 75);

        return $this->aws->storeImageData((string) $editedImage, 'edited_' . $postId);
    }
}
