<?php

namespace Rogue\Services;

use Rogue\Jobs\SendPostToPhoenix;
use Rogue\Models\Post;
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
        $this->resolvePostRepository($data[0]['event_type']);

        $updatedPosts = $this->repository->reviews($data);

        return $updatedPosts;
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
        switch ($type) {
            case 'post_photo':
                $this->repository = app('Rogue\Repositories\PhotoRepository');
                break;
            default:
                throw new HttpException(405, 'Not a valid post type');
                break;
        }
    }
}
