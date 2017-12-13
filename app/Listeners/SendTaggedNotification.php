<?php

namespace Rogue\Listeners;

use Notification;
use Rogue\Events\PostTagged;
use Rogue\Notifications\SlackTagNotification;

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
        $tag = $event->tag;

        info('Post tagged Good For Storytelling:', ['tag' => $tag->tag_name]);

        if ($tag->tag_slug === 'good-for-storytelling') {
            Notification::route('slack', config('services.slack.url'))
                ->notify(new SlackTagNotification($post));
        }
    }
}
