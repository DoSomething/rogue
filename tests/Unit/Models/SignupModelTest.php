<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Rogue\Models\Group;
use Rogue\Models\Signup;

class SignupModelTest extends TestCase
{
    /**
     * Test expected payload for Blink.
     *
     * @return void
     */
    public function testBlinkPayload()
    {
        $group = factory(Group::class)->create();
        $signup = factory(Signup::class)->create([
            'group_id' => $group->id,
            'referrer_user_id' => $this->faker->northstar_id,
        ]);

        $result = $signup->toBlinkPayload();

        $this->assertEquals($result['group_id'], $group->id);
        $this->assertEquals($result['group_name'], $group->name);
        $this->assertEquals($result['group_type_id'], $group->group_type_id);
        $this->assertEquals($result['group_type_name'], $group->group_type->name);
        $this->assertEquals($result['referrer_user_id'], $signup->referrer_user_id);
    }

    /**
     * Test expected payload when various attributes are not set.
     *
     * @return void
     */
    public function testBlinkPayloadForNullValues()
    {
        $signup = factory(Signup::class)->create();

        $result = $signup->toBlinkPayload();

        $this->assertEquals($result['group_id'], null);
        $this->assertEquals($result['group_name'], null);
        $this->assertEquals($result['group_type_id'], null);
        $this->assertEquals($result['group_type_name'], null);
        $this->assertEquals($result['referrer_user_id'], null);
    }
}
