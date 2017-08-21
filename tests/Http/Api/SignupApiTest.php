<?php

namespace Tests\Http\Api;

use Tests\BrowserKitTestCase;
use DoSomething\Gateway\Blink;

class SignupApiTest extends BrowserKitTestCase
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
        $campaignRunId = $this->faker->randomNumber(4);

        // Mock the Blink API call.
        $this->mock(Blink::class)->shouldReceive('userSignup');

        $this->withRogueApiKey()->json('POST', 'api/v2/signups', [
            'northstar_id'     => $northstarId,
            'campaign_id'      => $campaignId,
            'campaign_run_id'  => $campaignRunId,
            'source'           => 'the-fox-den',
        ]);

        // Make sure we get the 201 Created response
        $this->assertResponseStatus(201);
        $this->seeJson([
            'northstar_id' => $northstarId,
            'campaign_id' => $campaignId,
            'campaign_run_id' => $campaignRunId,
            'signup_source' => 'the-fox-den',
            'quantity' => null,
            'why_participated' => null,
        ]);

        // Make sure the signup is persisted.
        $this->seeInDatabase('signups', [
            'northstar_id' => $northstarId,
            'campaign_id' => $campaignId,
            'campaign_run_id' => $campaignRunId,
        ]);
    }
}
