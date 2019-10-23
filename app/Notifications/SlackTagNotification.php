<?php

namespace Rogue\Notifications;

use Rogue\Models\Tag;
use Rogue\Models\Post;
use Rogue\Services\GraphQL;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

const SLACK_NOTIFICATION_QUERY = '
    query SlackNotificationQuery($userId: String!, $adminId: String!) {
        user(id: $userId) {
            displayName
        }
        admin: user(id: $adminId) {
            displayName
        }
    }
';

class SlackTagNotification extends Notification
{
    use Queueable;

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
     * The admin who tagged this post.
     *
     * @var string;
     */
    public $adminId;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Post $post, Tag $tag, $adminId)
    {
        $this->post = $post;
        $this->tag = $tag;
        $this->adminId = $adminId;
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

        // Get the user & reviewer's names for the notification:
        $data = app(GraphQL::class)->query(SLACK_NOTIFICATION_QUERY, [
            'userId' => $this->post->northstar_id,
            'adminId' => $this->adminId,
        ]);

        $userName = $data['user']['displayName'];
        $adminName = $data['admin']['displayName'];

        return (new SlackMessage)
            ->from('Rogue', ':tonguecat:')
            ->content($adminName . ' just tagged this post as "' . $this->tag->tag_name . '":')
            ->attachment(function ($attachment) use ($userName, $adminName) {
                $permalink = route('signups.show', [$this->post->signup_id], true);

                $attachment->title($userName . '\'s submission for "' . $this->post->campaign->internal_title . '"', $permalink)
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
