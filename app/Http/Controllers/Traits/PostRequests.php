<?php

namespace Rogue\Http\Controllers\Traits;

use Rogue\Models\Post;
use Illuminate\Http\Request;
use Rogue\Http\Requests\PostRequest;

trait PostRequests
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $transactionId = incrementTransactionId($request);

        $signup = $this->signups->get($request['northstar_id'], $request['campaign_id'], $request['campaign_run_id']);

        $updating = ! is_null($signup);

        // @TODO - should we eventually throw an error if a signup doesn't exist before a post is created? I create one here because we haven't implemented sending signups to rogue yet, so it will have to create a signup record for all posts.
        if (! $updating) {
            $signup = $this->signups->create($request->all());

            // Send the data to the PostService class which will handle determining
            // which type of post we are dealing with and which repostitory to use to actually create the post.
            $post = $this->posts->create($request->all(), $signup->id, $transactionId);

            $code = 200;

            return $this->item($post);
        } else {
            $post = $this->posts->update($signup, $request->all(), $transactionId);

            $code = 201;

            if (isset($request['file'])) {
                return $this->item($post);
            } else {
                return $signup;
            }
        }
    }

    public function index(Request $request)
    {
        // Create an empty Post query, which we can filter and paginate
        $query = $this->newQuery(Post::class)
            // Eagerly load the count of reactions per post.
            ->withCount('reactions')
            // Only return posts that have been approved by a campaign lead...
            ->where('status', 'accepted')
            // ...and haven't been hidden from the gallery.
            ->withoutTags(['Hide In Gallery']);

        $filters = $request->query('filter');

        if (! empty($filters)) {
            // Only return Posts for the given campaign_id (if there is one)
            if (array_has($filters, 'campaign_id')) {
                // $query = $query->where('campaign_id', '=', $filters['campaign_id']);
                $query = $query->whereHas('signup', function ($query) {
                    $query->where('campaign_id', '=', 1631);
                });
            }

            // Only return Posts for the given northstar_id (if there is one)
            if (array_has($filters, 'northstar_id')) {
                $query = $query->where('signups.northstar_id', '=', $filters['northstar_id']);
            }

            // Only return Posts for the given status (if there is one)
            if (array_has($filters, 'status')) {
                $query = $query->where('status', '=', $filters['status']);
            }

            // Exclude Posts if exclude param is present
            if (array_has($filters, 'exclude')) {
                $query = $query->whereNotIn('posts.id', explode(',', $filters['exclude']));
            }
        }

        // If user param is passed, return whether or not the user has liked the particular post.
        if ($request->query('as_user')) {
            $userId = $request->query('as_user');
            $query = $query->with(['reactions' => function ($query) use ($userId) {
                $query->where('northstar_id', '=', $userId);
            }]);
        }

        // Eager load signups.
        $query->with('signup');

        return $this->paginatedCollection($query, $request);
    }
}
