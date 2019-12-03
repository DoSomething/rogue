<?php

namespace Rogue\Providers;

use Rogue\Models\Post;
use Rogue\Models\Event;
use Rogue\Models\Review;
use Rogue\Models\Signup;
use Rogue\Jobs\RejectPost;
use Rogue\Models\ActionStat;
use Illuminate\Support\ServiceProvider;

class ModelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // When a Signup's why_participated, quantity, quantity_pending, or deleted_at are changed, create an event.
        // @TODO: when we move quantity on the post, we'll want to remove this check below.
        Signup::saved(function ($signup) {
            if ($signup->isDirty('why_participated') || $signup->isDirty('quantity') || $signup->isDirty('quantity_pending') || $signup->isDirty('deleted_at') || $signup->isDirty('source')) {
                $signup->events()->create([
                    'content' => $signup->toJson(),
                    // Use the authenticated user if coming from the web,
                    // otherwise use the id of the user in the request.
                    'user' => auth()->id() ? auth()->id() : $signup->northstar_id,
                ]);
            }
        });

        // When Posts are created reject test events.
        Post::created(function ($post) {
            if (in_array($post->text, ['Test runscope upload', 'caption_ghost_test'])) {
                // The post will delay for 2 minutes before being rejected to assure tests are running normally
                RejectPost::dispatch($post)->delay(now()->addMinutes(2));
            }
        });

        // When Posts are saved create an event for them.
        Post::saved(function ($post) {
            $post->events()->create([
                // @TODO: this should include the tags with the post
                'content' => $post->toJson(),
                // Use the authenticated user if coming from the web,
                // otherwise use the id of the user in the request.
                'user' => auth()->id() ? auth()->id() : $post->northstar_id,
            ]);
        });

        // When Reviews are saved create an event for them.
        Review::saved(function ($review) {
            info('review', ['campaign' => $review->post->campaign_id]);

            // Update the "counter cache" on this campaign:
            $review->post->campaign->refreshCounts();

            // If this post is associated with a school, update the school action stats.
            $schoolId = $review->post->school_id;

            if ($schoolId) {
                $actionId = $review->post->action_id;
                ActionStat::updateOrCreate(
                    ['action_id' => $actionId, 'school_id' => $schoolId],
                    ['accepted_quantity' => Post::getAcceptedQuantitySum($actionId, $schoolId)]
                );
            }

            $review->events()->create([
                'content' => $review->toJson(),
                'user' => $review->admin_northstar_id,
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
