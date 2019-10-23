<?php

namespace Rogue\Notifications;

use Rogue\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class SlackTagNotification extends Notification
{
    use Queueable;

    /*
     * Post Instance
     *
     * @var Rogue\Models\Post;
     */
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
        if (! config('services.slack.url')) {
            return;
        }

        return (new SlackMessage)
            ->from('Rogue', ':tonguecat:')
            ->content('Another badass member submitted a post!')
            ->attachment(function ($attachment) {
                $attachment->title('Click here for more details in Rogue! We\'ll be adding member info. in Slack soon.', route('signups.show', [$this->post->signup_id], true))
                        ->fields([
                            'Caption' => str_limit($this->post->text, 140),
                            'Why Participated' => str_limit($this->post->signup->why_participated),
                        ])
                        ->color(array_random(['#FCD116', '#23b7fb', '#4e2b63']))
                        ->image($this->post->getMediaUrl());
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
