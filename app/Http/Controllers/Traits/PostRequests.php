<?php

namespace Rogue\Http\Controllers\Traits;

use Rogue\Models\Post;
use Illuminate\Http\Request;
use Rogue\Http\Requests\PostRequest;
use Rogue\Http\Controllers\Traits\FiltersRequests;

trait PostRequests
{
    use FiltersRequests;

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

    /**
     * Returns Posts, filtered by params, if provided.
     * GET /posts
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $this->newQuery(Post::class)->with('signup')->withCount('reactions');

        $filters = $request->query('filter');
        $query = $this->filter($query, $filters, Post::$indexes);

        return $this->paginatedCollection($query, $request);
    }
}
