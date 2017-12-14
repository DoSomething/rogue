<?php

namespace Rogue\Events;

use Rogue\Models\Tag;
use Rogue\Models\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;

class PostTagged
{
    use Dispatchable, SerializesModels;

    public $post;
    public $tag;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Post $post, Tag $tag)
    {
        $this->post = $post;
        $this->tag = $tag;
    }
}
