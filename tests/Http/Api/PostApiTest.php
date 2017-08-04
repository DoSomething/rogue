<?php

class PostApiTest extends TestCase
{
    /**
     * Test that a POST request to /posts creates a new photo post.
     *
     * @return void
     */
    public function testCreatingAPost()
    {
        // Create test Post. Temporarily use the current test reportback data
        // array as the requests are the same.
        // Create an uploaded file.
        $file = $this->mockFile();

        $post = [
            'northstar_id'     => str_random(24),
            'campaign_id'      => $this->faker->randomNumber(4),
            'campaign_run_id'  => $this->faker->randomNumber(4),
            'quantity'         => $this->faker->numberBetween(10, 1000),
            'why_participated' => $this->faker->paragraph(3),
            'num_participants' => null,
            'caption'          => $this->faker->sentence(),
            'source'           => 'runscope',
            'remote_addr'      => '207.110.19.130',
            'file'             => $file,
            'crop_x'           => 0,
            'crop_y'           => 0,
            'crop_width'       => 100,
            'crop_height'      => 100,
            'crop_rotate'      => 90,
        ];

        // Mock sending image to AWS.
        Storage::shouldReceive('put')->andReturn(true);

        $this->withRogueApiKey()->json('POST', 'api/v2/posts', $post);

        $this->assertResponseStatus(200);

        $response = $this->decodeResponseJson();

        // Make sure the file_url is saved to the database.
        $this->seeInDatabase('posts', [
            'id' => $response['data']['id'],
            'signup_id' => $response['data']['signup_id'],
            'campaign_id' => $post['campaign_id'],
        ]);

        $this->seeInDatabase('signups', [
            'id' => $response['data']['signup_id'],
            'campaign_id' => $post['campaign_id'],
            'quantity' => $post['quantity'],
        ]);
    }
}
