<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Rogue\Models\Post;
use Rogue\Models\Group;
use Rogue\Models\Action;
use Rogue\Models\Signup;

class PostModelTest extends TestCase
{
    /**
     * Test that a post school_id is set when its group has a school_id.
     *
     * @return void
     */
    public function testSettingSchoolIdFromGroupId()
    {
        $action = factory(Action::class)->create();
        $group = factory(Group::class)->create([
            'school_id' =>  $this->faker->unique()->school_id,
        ]);

        $groupPostWithoutSchool = factory(Post::class)->states('voter-reg')->create([
            'northstar_id' => $this->faker->northstar_id,
            'action_id' => $action->id,
            'group_id' => $group->id,
        ]);

        $this->assertEquals($groupPostWithoutSchool->school_id, $group->school_id);

        $groupPostWithSchool = factory(Post::class)->states('voter-reg')->create([
            'northstar_id' => $this->faker->northstar_id,
            'action_id' => $action->id,
            'group_id' => $group->id,
            'school_id' => $this->faker->unique()->school_id,
        ]);

        $this->assertNotEquals($groupPostWithSchool->school_id, $group->school_id);
    }

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
        $action = factory(Action::class)->create([
            'volunteer_credit' => true,
        ]);
        $group = factory(Group::class)->create();
        $post = factory(Post::class)->create([
            'action_id' => $action->id,
            'group_id' => $group->id,
            'referrer_user_id' => $this->faker->northstar_id,
            'school_id' => $this->faker->school_id,
        ]);

        $result = $post->toBlinkPayload();

        $this->assertEquals($result['group_id'], $group->id);
        $this->assertEquals($result['group_name'], $group->name);
        $this->assertEquals($result['group_type_id'], $group->group_type_id);
        $this->assertEquals($result['group_type_name'], $group->group_type->name);
        $this->assertEquals($result['referrer_user_id'], $post->referrer_user_id);
        $this->assertEquals($result['school_id'], $post->school_id);

        // Test expected data was retrieved from GraphQL.
        $this->assertEquals($result['campaign_slug'], 'test-example-campaign');
        $this->assertEquals($result['campaign_title'], 'Test Example Campaign');
        $this->assertEquals($result['school_name'], 'San Dimas High School');

        // Test expected post->action attributes were added to the Blink payload.
        $this->assertEquals($result['volunteer_credit'], $action->volunteer_credit);
    }

    /**
     * Test expected payload when various attributes are not set.
     *
     * @return void
     */
    public function testBlinkPayloadForNullValues()
    {
        $post = factory(Post::class)->create();

        $result = $post->toBlinkPayload();

        $this->assertEquals($result['group_id'], null);
        $this->assertEquals($result['group_name'], null);
        $this->assertEquals($result['group_type_id'], null);
        $this->assertEquals($result['group_type_name'], null);
        $this->assertEquals($result['school_id'], null);
        $this->assertEquals($result['school_name'], null);
        $this->assertEquals($result['referrer_user_id'], null);
    }

    /**
     * Test expected payload for a referral post event.
     *
     * @return void
     */
    public function testGetReferralPostEventPayload()
    {
        $group = factory(Group::class)->create();
        $post = factory(Post::class)->create([
            'group_id' => $group->id,
            'northstar_id' =>  $this->faker->unique()->northstar_id,
            'referrer_user_id' => $this->faker->unique()->northstar_id,
        ]);

        $result = $post->getReferralPostEventPayload();

        $this->assertEquals($result['action_id'], $post->action_id);
        $this->assertEquals($result['created_at'], $post->created_at->toIso8601String());
        $this->assertEquals($result['group_id'], $group->id);
        $this->assertEquals($result['group_name'], $group->name);
        $this->assertEquals($result['group_type_id'], $group->group_type->id);
        $this->assertEquals($result['group_type_name'], $group->group_type->name);
        $this->assertEquals($result['id'], $post->id);
        $this->assertEquals($result['status'], $post->status);
        $this->assertEquals($result['type'], $post->type);
        $this->assertEquals($result['updated_at'], $post->updated_at->toIso8601String());
        $this->assertEquals($result['user_id'], $post->northstar_id);

        // Test expected data was retrieved from GraphQL.
        $this->assertEquals($result['user_display_name'], 'Daisy D.');
    }
}
