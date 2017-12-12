<?php

namespace Rogue\Listeners;

use Rogue\Events\PostTagged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Rogue\Notifications\SlackTagNotification;
use Notification;

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

        info('Post tagged:', ['tag' => $tagName]);

        // Notification::route('slack', '#team-bleed')
        //     ->route('nexmo', '5555555555')
        //     ->notify(new SlackTagNotification($post));

        Notification::route("slack", 'https://hooks.slack.com/services/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')
            ->notify(new SlackTagNotification($post));
    }
}
