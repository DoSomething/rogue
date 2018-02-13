<?php

namespace Rogue\Services\Three;

use Rogue\Models\Post;
use Rogue\Jobs\SendPostToBlink;
use Rogue\Jobs\SendPostToQuasar;
use Rogue\Jobs\SendDeletedPostToQuasar;
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
     * @param string $authenticatedUserRole
     *
     * @return \Rogue\Models\Post
     */
    public function create($data, $signupId, $authenticatedUserRole = null)
    {
        $post = $this->repository->create($data, $signupId, $authenticatedUserRole);

        // Send to Blink unless 'dont_send_to_blink' is TRUE
        $should_send_to_blink = ! (array_key_exists('dont_send_to_blink', $data) && $data['dont_send_to_blink']);

        // Save the new post in Customer.io, via Blink.
        if (config('features.blink') && $should_send_to_blink) {
            SendPostToBlink::dispatch($post);
        }

        SendPostToQuasar::dispatch($post);

        // Log that a post was created.
        info('post_created', ['id' => $post->id, 'signup_id' => $post->signup_id]);

        return $post;
    }

    /**
     * Handles all business logic around updating posts.
     *
     * @param \Rogue\Models\Post $post
     * @param array $data
     * @return \Rogue\Models\Post
     */
    public function update($post, $data)
    {
        $post = $this->repository->update($post, $data);

        // Save the new post in Customer.io, via Blink,
        // unless 'dont_send_to_blink' is TRUE.
        $should_send_to_blink = ! (array_key_exists('dont_send_to_blink', $data) && $data['dont_send_to_blink']);
        if (config('features.blink') && $should_send_to_blink) {
            SendPostToBlink::dispatch($post);
        }

        SendPostToQuasar::dispatch($post);

        // Log that a post was updated.
        info('post_updated', ['id' => $post->id, 'signup_id' => $post->signup_id]);

        return $post;
    }

    /**
     * Handles all business logic around reviewing posts.
     *
     * @param \Rogue\Models\Post $post
     * @param array $data
     * @return \Rogue\Models\Post
     */
    public function review($post, $data, $comment)
    {
        $post = $this->repository->reviews($post, $data, $comment);

        SendPostToQuasar::dispatch($post);


        // Log that a post was reviewed.
        info('post_reviewed', [
            'id' => $post->id,
            'admin_northstar_id' => auth()->id(),
            'status' => $post->status,
        ]);

        return $post;
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

        $trashed = $this->repository->destroy($postId);

        // Dispatch job to send post to Quasar
        if (config('features.pushToQuasar')) {
            SendDeletedPostToQuasar::dispatch($postId);
        }

        return $trashed;
    }
}
