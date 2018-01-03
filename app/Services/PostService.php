<?php

namespace Rogue\Services;

use Rogue\Models\Post;
use Rogue\Jobs\SendPostToBlink;
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
     * @param PostRepository $posts
     * @param Blink $blink
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
     * @return \Rogue\Models\Post
     */
    public function create($data, $signupId, $transactionId)
    {
        $post = $this->repository->create($data, $signupId);

        // Send to Blink unless 'dont_send_to_blink' is TRUE
        $should_send_to_blink = ! (array_key_exists('dont_send_to_blink', $data) && $data['dont_send_to_blink']);

        // Save the new post in Customer.io, via Blink.
        if (config('features.blink') && $should_send_to_blink) {
            SendPostToBlink::dispatch($post);
        }

        // Add new transaction id to header.
        request()->headers->set('X-Request-ID', $transactionId);

        // Log that a post was created.
        logger()->info('post_created', ['id' => $post->id, 'signup_id' => $post->signup_id]);

        return $post;
    }

    /**
     * Handles all business logic around updating posts.
     *
     * @param \Rogue\Models\Signup $signup
     * @param array $data
     * @param string $transactionId
     * @return \Rogue\Models\Post|\Rogue\Models\Signup
     */
    public function update($signup, $data, $transactionId)
    {
        $postOrSignup = $this->repository->update($signup, $data);

        // Send to Blink unless 'dont_send_to_blink' is TRUE
        $should_send_to_blink = ! (array_key_exists('dont_send_to_blink', $data) && $data['dont_send_to_blink']);

        // Save the new post in Customer.io, via Blink.
        if (config('features.blink') && $postOrSignup instanceof Post && $should_send_to_blink) {
            SendPostToBlink::dispatch($postOrSignup);
        }

        // Add new transaction id to header.
        request()->headers->set('X-Request-ID', $transactionId);

        return $postOrSignup;
    }

    /**
     * Handle all business logic around deleting a post.
     *
     * @param int $postId
     * @return bool
     */
    public function destroy($postId)
    {
        return $this->repository->destroy($postId);
    }
}
