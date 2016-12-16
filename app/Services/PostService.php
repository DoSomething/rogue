<?php

namespace Rogue\Services;

use Rogue\Jobs\SendPostToPhoenix;
use Rogue\Repositories\PhotoRepository;

class PostService
{
    /*
     * PhotoRepository Instance
     *
     * @var Rogue\Repositories\PhotoRepository;
     */
    protected $post;

    /**
     * Constructor
     *
     * @param \Rogue\Repositories\PhotoRepository $photos
     */
    public function __construct(PhotoRepository $post)
    {
        // @TODO - Eventually would want to figure out a smarter way of resolving different repositories to use for a post i.e. Video, text, etc.
        $this->post = $post;
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
        $post = $this->post->create($data, $signupId);

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
