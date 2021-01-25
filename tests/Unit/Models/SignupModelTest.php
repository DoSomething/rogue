<?php

namespace Tests\Unit\Models;

use App\Models\Group;
use App\Models\Signup;
use Tests\TestCase;

class SignupModelTest extends TestCase
{
    /**
     * Test expected payload for Customer.io.
     *
     * @return void
     */
    public function testCustomerIoPayload()
    {
        $group = factory(Group::class)->create();
        $signup = factory(Signup::class)->create([
            'group_id' => $group->id,
            'referrer_user_id' => $this->faker->northstar_id,
        ]);

        $result = $signup->toCustomerIoPayload();

        $this->assertEquals($result['group_id'], $group->id);
        $this->assertEquals($result['group_name'], $group->name);
        $this->assertEquals($result['group_type_id'], $group->group_type_id);
        $this->assertEquals(
            $result['group_type_name'],
            $group->group_type->name,
        );
        $this->assertEquals(
            $result['referrer_user_id'],
            $signup->referrer_user_id,
        );
    }

    /**
     * Test expected payload for a referral signup event.
     *
     * @return void
     */
    public function testGetReferralSignupEventPayload()
    {
        $signup = factory(Signup::class)->create([
            'northstar_id' => $this->faker->unique()->northstar_id,
            'referrer_user_id' => $this->faker->unique()->northstar_id,
        ]);

        $result = $signup->getReferralSignupEventPayload();

        $this->assertEquals(
            $result['created_at'],
            $signup->created_at->toIso8601String(),
        );
        $this->assertEquals($result['id'], $signup->id);
        $this->assertEquals(
            $result['updated_at'],
            $signup->updated_at->toIso8601String(),
        );
        $this->assertEquals($result['user_id'], $signup->northstar_id);
        $this->assertEquals($result['campaign_id'], $signup->campaign_id);

        // Test expected data was retrieved from GraphQL.
        $this->assertEquals($result['user_display_name'], 'Daisy D.');
        $this->assertEquals($result['campaign_title'], 'Test Example Campaign');
    }
}
