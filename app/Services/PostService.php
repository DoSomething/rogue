<?php

namespace Rogue\Services;

use Rogue\Models\Photo;
use Rogue\Jobs\SendPostToPhoenix;
use Rogue\Repositories\PostContract;

class PostService
{
    /*
     * Photo repository instance.
     *
     * @var Rogue\Repositories\PostContract;
     */
    protected $posts;

    /**
     * Constructor
     *
     * @param \Rogue\Repositories\PhotoRepository $photos
     */
    public function __construct(PostContract $posts)
    {
        $this->posts = $posts;
    }

    /*
     * Handles all business logic around creating posts.
     *
     * @param array $data
     * @param int $signupId
     * @param string $transactionId
     * @return Illuminate\Database\Eloquent\Model $model
     */
    public function create($data, $signupId, $transactionId)
    {
        $post = $this->posts->create($data, $signupId);

        // Add new transaction id to header.
        request()->headers->set('X-Request-ID', $transactionId);

        // POST reportback back to Phoenix, unless told not to.
        // If request fails, record in failed_jobs table.
        if (! isset($data['do_not_forward'])) {
            dispatch(new SendPostToPhoenix($post));
        }

        return $post;
    }
}
