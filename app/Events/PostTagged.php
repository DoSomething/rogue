<?php

namespace Rogue\Events;

use Rogue\Models\Tag;
use Rogue\Models\Post;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class PostTagged
{
    use Dispatchable, SerializesModels;

    /*
     * Post Instance
     *
     * @var Rogue\Models\Post;
     */
    public $post;

    /*
     * Tag Instance
     *
     * @var Rogue\Models\Tag;
     */
    public $tag;

    /*
     * The user ID who tagged this post.
     *
     * @var string
     */
    public $adminId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Post $post, Tag $tag)
    {
        $this->post = $post;
        $this->tag = $tag;
        $this->adminId = auth()->id();
    }
}
