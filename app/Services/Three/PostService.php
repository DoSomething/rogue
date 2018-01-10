<?php

namespace Rogue\Services\Three;

use Rogue\Models\Post;
use Rogue\Jobs\SendPostToBlink;
use Rogue\Repositories\Three\PostRepository;

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
     * @return \Rogue\Models\Post
     */
    public function create($data, $signupId)
    {
        $post = $this->repository->create($data, $signupId);

        // Send to Blink unless 'dont_send_to_blink' is TRUE
        $should_send_to_blink = ! (array_key_exists('dont_send_to_blink', $data) && $data['dont_send_to_blink']);

        // Save the new post in Customer.io, via Blink.
        if (config('features.blink') && $should_send_to_blink) {
            // @TODO: now, the below will send quantity in the payload. Do we need to notify Blink of this?
            SendPostToBlink::dispatch($post);
        }

        // Log that a post was created.
        info('post_created', ['id' => $post->id, 'signup_id' => $post->signup_id]);

        return $post;
    }

    /**
     * Handles all business logic around updating posts.
     *
     * @param \Rogue\Models\Signup $signup
     * @param array $data
     * @return \Rogue\Models\Post|\Rogue\Models\Signup
     */
    public function update($signup, $data)
    {
        $postOrSignup = $this->repository->update($signup, $data);

        // Send to Blink unless 'dont_send_to_blink' is TRUE
        $should_send_to_blink = ! (array_key_exists('dont_send_to_blink', $data) && $data['dont_send_to_blink']);

        // Save the new post in Customer.io, via Blink.
        if (config('features.blink') && $postOrSignup instanceof Post && $should_send_to_blink) {
            SendPostToBlink::dispatch($postOrSignup);

            // Log that a post was created.
            info('post_created', ['id' => $postOrSignup->id, 'signup_id' => $postOrSignup->signup_id]);
        }

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
        info('post_deleted', [
            'id' => $postId,
        ]);

        return $this->repository->destroy($postId);
    }
}
