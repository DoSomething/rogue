<?php

namespace Tests\Http\Legacy\Two;

use Tests\TestCase;
use DoSomething\Gateway\Blink;

class SignupApiTest extends TestCase
{
    /**
     * Test that a POST request to /signups creates a new signup.
     *
     * @group creatingAPhoto
     * @return void
     */
    public function testCreatingASignup()
    {
        $northstarId = '54fa272b469c64d7068b456a';
        $campaignId = $this->faker->randomNumber(4);

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignup');

        $response = $this->withRogueApiKey()->postJson('api/v2/signups', [
            'northstar_id'     => $northstarId,
            'campaign_id'      => $campaignId,
            'source'           => 'the-fox-den',
            'details'          => 'affiliate-messaging',
        ]);

        // Make sure we get the 201 Created response
        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'northstar_id' => $northstarId,
                'campaign_id' => $campaignId,
                'signup_source' => 'the-fox-den',
                'quantity' => null,
                'why_participated' => null,
            ],
        ]);

        // Make sure the signup is persisted.
        $this->assertDatabaseHas('signups', [
            'northstar_id' => $northstarId,
            'campaign_id' => $campaignId,
            'details' => 'affiliate-messaging',
        ]);
    }
}
