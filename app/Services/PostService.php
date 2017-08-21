<?php

namespace Rogue\Services;

use DoSomething\Gateway\Blink;
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
     * Blink API client.
     *
     * @var \DoSomething\Gateway\Blink
     */
    protected $blink;

    /**
     * Constructor
     *
     * @param PostRepository $posts
     * @param Blink $blink
     */
    public function __construct(PostRepository $posts, Blink $blink)
    {
        $this->repository = $posts;
        $this->blink = $blink;
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

        // Save the new post in Customer.io, via Blink.
        if (config('features.blink')) {
            $payload = $post->toBlinkPayload();
            $this->blink->userSignupPost($payload);
        }

        // Add new transaction id to header.
        request()->headers->set('X-Request-ID', $transactionId);

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
