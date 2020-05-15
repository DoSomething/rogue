<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\Signup;

class PostModelTest extends TestCase
{
    /**
     * Test that a signup's updated_at updates when a post is updated.
     *
     * @return void
     */
    public function testUpdatingSignupTimestampWhenPostIsUpdated()
    {
        // Freeze time since we're making assertions on timestamps.
        $this->mockTime('8/3/2017 14:00:00');

        // Create a signup and a post, and associate them to each other.
        $signup = factory(Signup::class)->create();
        $post = factory(Post::class)->create();
        $post->signup()->associate($signup);

        // And then later on, we'll update the post...
        $this->mockTime('8/3/2017 16:52:00');
        $post->update(['caption' => 'new caption']);

        // Make sure the signup and post's updated_at are both updated.
        $this->assertEquals('2017-08-03 16:52:00', (string) $post->fresh()->updated_at);
        $this->assertEquals('2017-08-03 16:52:00', (string) $signup->fresh()->updated_at);
    }

    /**
     * Test that the siblings relationship returns other posts.
     *
     * @return void
     */
    public function testSiblingRelationship()
    {
        factory(Signup::class, 5)->create()
            ->each(function ($signup) {
                $signup->posts()->saveMany(factory(Post::class, 3)->states('photo', 'accepted')->create());
            });

        // Grab any old post.
        $post = Signup::all()->first()->posts->first();

        // Asking for the siblings of the post, should only give other
        // posts with the same `signup_id` (including itself).
        $this->assertCount(3, $post->siblings);
        $this->assertEquals($post->signup_id, $post->siblings[0]->signup_id);
    }

    /**
     * Test expected payload for Blink.
     *
     * @return void
     */
    public function testBlinkPayload()
    {
        $post = factory(Post::class)->create([
            'school_id' => 'Example School ID',
        ]);
        $result = $post->toBlinkPayload();

        // Test expected data was retrieved from GraphQL.
        $this->assertEquals($result['campaign_slug'], 'test-example-campaign');
        $this->assertEquals($result['campaign_title'], 'Test Example Campaign');
        $this->assertEquals($result['school_name'], 'San Dimas High School');

        $post = factory(Post::class)->create([
            'school_id' => null,
            'referrer_user_id' => null,
        ]);
        $result = $post->toBlinkPayload();

        $this->assertEquals($result['school_name'], null);
        $this->assertEquals($result['referrer_user_id'], null);

        $referrerUserId = $this->faker->northstar_id;
        $post = factory(Post::class)->create([
            'referrer_user_id' => $referrerUserId,
        ]);
        $result = $post->toBlinkPayload();

        $this->assertEquals($result['referrer_user_id'], $referrerUserId);
    }

    /**
     * Test expected payload for a referral post event.
     *
     * @return void
     */
    public function testGetReferralPostEventPayload()
    {
        $post = factory(Post::class)->create([
            'northstar_id' =>  $this->faker->unique()->northstar_id,
            'referrer_user_id' => $this->faker->unique()->northstar_id,
        ]);

        $result = $post->getReferralPostEventPayload();

        $this->assertEquals($result['action_id'], $post->action_id);
        $this->assertEquals($result['created_at'], $post->created_at->toIso8601String());
        $this->assertEquals($result['id'], $post->id);
        $this->assertEquals($result['status'], $post->status);
        $this->assertEquals($result['type'], $post->type);
        $this->assertEquals($result['updated_at'], $post->updated_at->toIso8601String());
        $this->assertEquals($result['user_id'], $post->northstar_id);

        // Test expected data was retrieved from GraphQL.
        $this->assertEquals($result['user_display_name'], 'Daisy D.');
    }
}
