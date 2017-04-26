<?php

namespace Rogue\Services;

use Rogue\Models\Post;
use Rogue\Jobs\SendPostToPhoenix;
use Rogue\Repositories\PostRepository;

class PostService
{
    /*
     * PostRepository Instance
     *
     * @var Rogue\Repositories\PostRepository;
     */
    protected $repository;

    /**
     * Constructor
     *
     * @param \Rogue\Repositories\PostRepository $posts
     */
    public function __construct(PostRepository $posts)
    {
        $this->repository = $posts;
    }

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
        $post = $this->repository->update($signup, $data);

        // @TODO: This will is only temporary and will be removed!
        // If this is a signup update, get the most recent post.
        // If there is a quantity_pending, this is a signup.
        if ($post->quantity || $post->quantity_pending) {
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
}
