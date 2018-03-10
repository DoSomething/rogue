<?php

namespace Rogue\Console\Commands;

use Rogue\Models\Signup;
use Illuminate\Console\Command;
use Rogue\Jobs\SendPostToQuasar;

class PostQuantity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rogue:postquantity {start=1} {sleeptime=0} {chunk=1000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add the quantity from the corresponding signup onto the corresponding post.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Signup that we should start with
        $start = $this->argument('start');
        $chunk = $this->argument('chunk');

        // Get all signups starting from $start in order of ID
        Signup::where('id', '>=', $start)->orderBy('id')->with('posts')->chunk($chunk, function ($signups) {
            foreach ($signups as $signup) {
                // Get the posts for the signup
                $posts = $signup->posts;

                // If no posts, move on
                if ($posts->isEmpty()) {
                    continue;
                }

                // Sum quant of posts
                $postQuantityTotal = $posts->sum('quantity');

                // If >0, see if any posts have null quant
                if ($postQuantityTotal) {
                    $postQuantities = $posts->pluck('quantity')->toArray();

                    // If none have null quant, continue we are done here
                    if (! in_array(null, $postQuantities, true)) {
                        continue;
                    }

                    // If some have null quant we need to fill them in
                    $missingQuantity = $signup->getQuantity() - $postQuantityTotal;
                    $this->putQuantityOnPosts($posts, $missingQuantity);

                    // Move on to the next signup
                    continue;
                }

                // If no posts have a quantity yet
                $quantity = $signup->getQuantity();
                $this->putQuantityOnPosts($posts, $quantity);
            }
            info('rogue:postquantity: Updated posts for signup ' . $signup->id);
        });
        // We did it!
        info('rogue:postquantity: ALL DONE');
    }

    public function putQuantityOnPosts($posts, $quantity)
    {
        // Signup that we should start with
        $sleeptime = $this->argument('sleeptime');

        // Put all the quantity on the most recent accepted post (based on creation)
        $acceptedPosts = $posts->where('status', 'accepted')->sortByDesc('created_at');
        if ($acceptedPosts->isNotEmpty()) {
            $mostRecentAcceptedPost = $acceptedPosts->first();
            $mostRecentAcceptedPost->quantity = $quantity;
            $mostRecentAcceptedPost->save();
            SendPostToQuasar::dispatch($mostRecentAcceptedPost);
            usleep($sleeptime);
        }
        // If no accepted posts, put quantity on most recent post (based on creation)
        else {
            $mostRecentPost = $posts->sortByDesc('created_at')->first();
            $mostRecentPost->quantity = $quantity;
            $mostRecentPost->save();
            SendPostToQuasar::dispatch($mostRecentPost);
            usleep($sleeptime);
        }
        // Put quantity of 0 on all other posts under this signup
        foreach ($posts as $post) {
            if (is_null($post->quantity)) {
                $post->quantity = 0;
                $post->save();
                SendPostToQuasar::dispatch($post);
                usleep($sleeptime);
            }
        }
    }
}
