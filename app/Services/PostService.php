<?php

namespace Rogue\Services;

use Rogue\Models\Post;
use Rogue\Jobs\SendPostToPhoenix;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PostService
{
    /*
     * Repository Instance
     *
     */
    protected $repository;

    /**
     * Handles all business logic around creating posts.
     *
     * @param array $data
     * @param int $signupId
     * @param string $transactionId
     * @return Illuminate\Database\Eloquent\Model $model
     */
    public function create($data, $signupId, $transactionId)
    {
        $this->resolvePostRepository($data['event_type']);

        $post = $this->repository->create($data, $signupId);

        // Add new transaction id to header.
        request()->headers->set('X-Request-ID', $transactionId);

        // POST reportback back to Phoenix, unless told not to.
        // If request fails, record in failed_jobs table.
        if (! isset($data['do_not_forward'])) {
            dispatch(new SendPostToPhoenix($post));
        }

        return $post;
    }

    /**
     * Handles all business logic around updating posts.
     *
     * @param \Rogue\Models\Signup $signup
     * @param array $data
     * @param string $transactionId
     *
     * @return \Illuminate\Database\Eloquent\Model $model
     */
    public function update($signup, $data, $transactionId)
    {
        $this->resolvePostRepository($data['event_type']);

        $post = $this->repository->update($signup, $data);

        // @TODO: This will is only temporary and will be removed!
        // If this is a signup update, get the most recent post.
        // If there is a quantity_pending, this is a signup.
        if ($post->quantity_pending) {
            $signupId = $post->id;
            // Find the post with this signup id.
            $post = Post::where('signup_id', $signupId)->first();
        }

        // Add new transaction id to header.
        request()->headers->set('X-Request-ID', $transactionId);

        // Post reportback back to Phoenix, unless told not to.
        // If request fails, record in failed_jobs table.
        if (! isset($data['do_not_forward'])) {
            dispatch(new SendPostToPhoenix($post, isset($data['file'])));
        }

        return $post;
    }

    /**
     * Handles all business logic around updating the posts(s)'s status after being reviewed.
     *
     * @param array $data
     *
     * @return
     */
    public function reviews($data)
    {
        // @TODO: this will need to be updated when other post types are introduced. Right now, all reviews are of photos so everything in this nested array will be a photo. However, if admins can review different types of posts (e.g. photos, videos, links) at once, we'll need to update the logic here.
        $this->resolvePostRepository($data[0]['event_type']);

        $reviewedPosts = $this->repository->reviews($data);

        // Add reportback items into an array.
        $reportbackItems = [];

        // Remove all Reportback Items so the transformer doesn't break.
        foreach ($reviewedPosts as $key => $reviewedPost) {
            if (isset($reviewedPost->reportback_id)) {
                array_push($reportbackItems, $reviewedPost);
                unset($reviewedPosts[$key]);
            }
        }

        // If all reviewed posts are reportback items, return $reportbackItems array to send to Reportback Items Transformer. Otherwise, return reviewed posts, omitting reportback items.
        if (empty($reviewedPosts)) {
            return $reportbackItems;
        } else {
            return $reviewedPosts;
        }
    }

    /**
     * Determines which type of post we trying to work with based on the passed 'event_type'
     *
     * @param $string $type
     * @throws HttpException
     * @return Rogue\Repostitories\PhotoRepository
     */
    protected function resolvePostRepository($type)
    {
        // Get the event type (type is anything after _).
        $type = explode('_', $type)[1];

        switch ($type) {
            case 'photo':
                $this->repository = app('Rogue\Repositories\PhotoRepository');
                break;
            default:
                throw new HttpException(405, 'Not a valid post type');
                break;
        }
    }
}
