<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;

class SignupApiTest extends TestCase
{
    use WithoutMiddleware;

    /*
     * Base URL for the Api.
     */
    protected $signupsApiUrl = 'api/v2/signups';

    /**
     * Test that a POST request to /signupss creates a new signup.
     *
     * @group creatingAPhoto
     * @return void
     */
    public function testCreatingASignup()
    {
    	// Create test signup
        $signup = [
            'northstar_id'     => str_random(24),
            'campaign_id'      => $this->faker->randomNumber(4),
            'campaign_run_id'  => $this->faker->randomNumber(4),
            'source'           => 'the-fox-den',
            'do_not_forward'   => true,
        ];

        // Send the signup and make sure the response has the data we expect
        $response = $this->json('POST', $this->signupsApiUrl, $signup)
        				 ->seeJson([
                            'northstar_id' => $signup['northstar_id'],
                            'campaign_id' => $signup['campaign_id'],
                            'campaign_run_id' => $signup['campaign_run_id'],
                            'signup_source' => 'the-fox-den',
                            'quantity' => null,
                            'why_participated' => null,
                        ]);

        // Make sure we get the 201 Created response
        $this->assertResponseStatus(201);

        $response = $this->decodeResponseJson();

        // Make sure the signup got created
        $this->seeInDatabase('signups', [
            'northstar_id' => $response['data']['northstar_id'],
        ]);
    }
}
