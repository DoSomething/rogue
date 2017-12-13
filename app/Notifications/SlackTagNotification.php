<?php

namespace Rogue\Notifications;

use Rogue\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class SlackTagNotification extends Notification
{
    use Queueable;

    public $post;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
                    ->from('Tongue Cat', ':tonguecat:')
                    ->content('Another badass member submitted a post!')
                    ->attachment(function ($attachment) {
                        $attachment->title('Click here for the full post on Rogue', route('signups.show', [$this->post->signup_id], true))
                                ->fields([
                                    'Caption' => str_limit($this->post->caption, 140),
                                    'Why Participated' => str_limit($this->post->signup->why_participated),
                                ])
                                ->color(array_random(['#FCD116', '#23b7fb', '#4e2b63']))
                                ->image($this->post->url);
                    });
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
