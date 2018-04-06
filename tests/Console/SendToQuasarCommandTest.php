<?php

namespace Tests\Console;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\Signup;
use Rogue\Jobs\SendPostToQuasar;
use Rogue\Jobs\SendSignupToQuasar;
use Illuminate\Support\Facades\Bus;

class SendToQuasarCommandTest extends TestCase
{
    public function testSendingWithNoArguments()
    {
        Bus::fake();

        $this->mockTime('8/3/2017 14:00:00');
        factory(Signup::class, 2)->create();
        factory(Post::class, 2)->create();

        $this->mockTime('2/4/2018 12:00:00');
        factory(Signup::class, 2)->create();
        factory(Post::class, 2)->create();

        $this->artisan('rogue:quasar');

        foreach (Signup::all() as $signup) {
            Bus::assertDispatched(SendSignupToQuasar::class, function ($job) use ($signup) {
                return $job->getSignupId() === $signup->id;
            });
        }

        foreach (Post::all() as $post) {
            Bus::assertDispatched(SendPostToQuasar::class, function ($job) use ($post) {
                return $job->getPostId() === $post->id;
            });
        }
    }

    public function testSendingWithStartArgument()
    {
        Bus::fake();

        // Create some signups and posts
        $this->mockTime('8/3/2017 14:00:00');
        $signupsNotSent = factory(Signup::class, 2)->create();
        $postsNotSent = factory(Post::class, 2)->create();

        $this->mockTime('2/4/2018 12:00:00');
        $signupsToSend = factory(Signup::class, 2)->create();
        $postsToSend = factory(Post::class, 2)->create();

        // Call the command
        $this->artisan('rogue:quasar', ['start' => '2018-02-04']);

        // Make sure that posts and signups that should have been sent were
        foreach ($signupsToSend as $signup) {
            Bus::assertDispatched(SendSignupToQuasar::class, function ($job) use ($signup, $signupsNotSent) {
                return $job->getSignupId() === $signup->id;
            });
        }

        foreach ($postsToSend as $post) {
            Bus::assertDispatched(SendPostToQuasar::class, function ($job) use ($post) {
                return $job->getPostId() === $post->id;
            });
        }

        // Make sure that posts and signups that should NOT have been sent weren't
        foreach ($signupsNotSent as $signup) {
            Bus::assertNotDispatched(SendSignupToQuasar::class, function ($job) use ($signup) {
                return $job->getSignupId() === $signup->id;
            });
        }

       foreach ($postsNotSent as $post) {
            Bus::assertNotDispatched(SendPostToQuasar::class, function ($job) use ($post) {
                return $job->getPostId() === $post->id;
            });
        }
    }

    public function testSendingWithStartAndEndArguments()
    {
        Bus::fake();

        // Create some signups and posts
        $this->mockTime('8/3/2017 14:00:00');
        $signupsNotSent = factory(Signup::class, 2)->create();
        $postsNotSent = factory(Post::class, 2)->create();

        $this->mockTime('2/4/2018 12:00:00');
        $oldSignupsToSend = factory(Signup::class, 2)->create();
        $oldPostsToSend = factory(Post::class, 2)->create();

        $this->mockTime('5/18/2018 09:00:00');
        $newSignupsToSend = factory(Signup::class, 2)->create();
        $newPostsToSend = factory(Post::class, 2)->create();

        $signupsToSend = $oldSignupsToSend->merge($newSignupsToSend);
        $postsToSend = $oldPostsToSend->merge($newPostsToSend);

        // Call the command
        $this->artisan('rogue:quasar', ['start' => '2018-02-04', 'end' => '2018-05-19']);

        // Make sure that posts and signups that should have been sent were
        foreach ($signupsToSend as $signup) {
            Bus::assertDispatched(SendSignupToQuasar::class, function ($job) use ($signup, $signupsNotSent) {
                return $job->getSignupId() === $signup->id;
            });
        }

        foreach ($postsToSend as $post) {
            Bus::assertDispatched(SendPostToQuasar::class, function ($job) use ($post) {
                return $job->getPostId() === $post->id;
            });
        }

        // Make sure that posts and signups that should NOT have been sent weren't
        foreach ($signupsNotSent as $signup) {
            Bus::assertNotDispatched(SendSignupToQuasar::class, function ($job) use ($signup) {
                return $job->getSignupId() === $signup->id;
            });
        }

       foreach ($postsNotSent as $post) {
            Bus::assertNotDispatched(SendPostToQuasar::class, function ($job) use ($post) {
                return $job->getPostId() === $post->id;
            });
        }
    }
}
