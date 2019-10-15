<?php

namespace Rogue\Managers;

use Rogue\Models\Post;
use Rogue\Services\Fastly;
use Rogue\Jobs\SendPostToQuasar;
use Rogue\Jobs\SendPostToCustomerIo;
use Rogue\Repositories\PostRepository;
use Rogue\Jobs\SendDeletedPostToQuasar;
use Rogue\Jobs\SendReviewedPostToCustomerIo;

class PostManager
{
    /**
     * The Fastly API client.
     *
     * @var Rogue\Services\Fastly
     */
    protected $fastly;

    /*
     * The post repository.
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
    public function __construct(PostRepository $posts, Fastly $fastly)
    {
        $this->repository = $posts;
        $this->fastly = $fastly;
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
            SendPostToCustomerIo::dispatch($post);
        }

        SendPostToQuasar::dispatch($post);

        // Log that a post was created.
        info('post_created', ['id' => $post->id, 'signup_id' => $post->signup_id, 'post_created_source' => $post->source]);

        return $post;
    }

    /**
     * Handles all business logic around updating posts.
     *
     * @param \Rogue\Models\Post $post
     * @param array $data
     * @param bool $log
     * @return \Rogue\Models\Post
     */
    public function update($post, $data, $log = true)
    {
        $post = $this->repository->update($post, $data);

        // Save the new post in Customer.io, via Blink,
        // unless 'dont_send_to_blink' is TRUE.
        $should_send_to_blink = ! (array_key_exists('dont_send_to_blink', $data) && $data['dont_send_to_blink']);
        if (config('features.blink') && $should_send_to_blink) {
            SendPostToCustomerIo::dispatch($post, $log);
        }

        SendPostToQuasar::dispatch($post, $log);

        if ($log) {
            // Log that a post was updated.
            info('post_updated', ['id' => $post->id, 'signup_id' => $post->signup_id]);
        }

        return $post;
    }

    /**
     * Handles all business logic around reviewing posts.
     *
     * @param \Rogue\Models\Post $post
     * @param array $data
     * @return \Rogue\Models\Post
     */
    public function review($post, $data, $comment = null, $admin = null)
    {
        $post = $this->repository->reviews($post, $data, $comment, $admin);

        SendPostToQuasar::dispatch($post);
        SendReviewedPostToCustomerIo::dispatch($post);

        // Log that a post was reviewed.
        info('post_reviewed', [
            'id' => $post->id,
            'admin_northstar_id' => $admin ? $admin : auth()->id(),
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
    public function destroy(Post $post)
    {
        $trashed = $this->repository->destroy($post->id);

        $this->fastly->purge($post);

        SendDeletedPostToQuasar::dispatch($post->id);

        info('post_deleted', ['id' => $post->id]);

        return $trashed;
    }
}
