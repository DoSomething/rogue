<?php

namespace Rogue\Listeners;

use Rogue\Events\PostTagged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTaggedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PostTagged  $event
     * @return void
     */
    public function handle(PostTagged $event)
    {
        $post = $event->post;
        $tagName = $event->tagName;

        info('Post tagged', ['tag' => $tagName]);
    }
}
