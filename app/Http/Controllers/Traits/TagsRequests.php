<?php

namespace Rogue\Http\Controllers\Traits;

use Rogue\Http\Requests\TagsRequest;

trait TagsRequests
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagsRequest $request)
    {
        $post = $this->post->find($request->post_id);

        $taggedPost = $this->post->tag($post, $request->tag_name);

        return $this->item($taggedPost);
    }
}
