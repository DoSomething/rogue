<?php

namespace Rogue\Services;

use Rogue\Jobs\SendPostToPhoenix;

class PostService
{
    /*
     * Repository Instance
     *
     */
    protected $repository;

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

    protected function resolvePostRepository($type)
    {
        switch ($type) {
            case 'post_photo':
                $this->repository = app('Rogue\Repositories\PhotoRepository');

                break;

            default:
                break;
        }
    }
}
