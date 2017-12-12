<?php

namespace Rogue\Events;

use Rogue\Models\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PostTagged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;
    public $tagName;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Post $post, $tagName)
    {
        $this->post = $post;
        $this->tagName = $tagName;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
