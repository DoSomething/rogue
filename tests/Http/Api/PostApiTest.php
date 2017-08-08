<?php

use Illuminate\Support\Facades\Storage;

class PostApiTest extends TestCase
{
    /**
     * Test that a POST request to /posts creates a new photo post.
     *
     * @return void
     */
    public function testCreatingAPost()
    {
        $northstar_id = $this->faker->uuid;
        $campaign_id = $this->faker->randomNumber(4);
        $campaign_run_id = $this->faker->randomNumber(4);
        $quantity = $this->faker->numberBetween(10, 1000);
        $caption = $this->faker->sentence;

        // Mock file system operations.
        $url = 'https://s3.amazonaws.com/ds-rogue-prod/uploads/reportback-items/edited_1.jpeg';
        Storage::shouldReceive('put')->andReturn(true);
        Storage::shouldReceive('url')->andReturn($url);

        // Create the post!
        $this->withRogueApiKey()->json('POST', 'api/v2/posts', [
            'northstar_id'     => $northstar_id,
            'campaign_id'      => $campaign_id,
            'campaign_run_id'  => $campaign_run_id,
            'quantity'         => $quantity,
            'why_participated' => $this->faker->paragraph,
            'num_participants' => null,
            'caption'          => $caption,
            'source'           => 'runscope',
            'remote_addr'      => $this->faker->ipv4,
            'file'             => $this->mockFile(),
            'crop_x'           => 0,
            'crop_y'           => 0,
            'crop_width'       => 100,
            'crop_height'      => 100,
            'crop_rotate'      => 90,
        ]);

        $this->assertResponseStatus(200);
        $this->seeJsonSubset([
            'data' => [
                'northstar_id' => $northstar_id,
                'status' => 'pending',
                'media' => [
                    'url' => $url,
                    'caption' => $caption,
                ],
            ],
        ]);

        // Make sure the signup & post are persisted to the database.
        $this->seeInDatabase('signups', [
            'campaign_id' => $campaign_id,
            'northstar_id' => $northstar_id,
            'quantity' => $quantity,
        ]);

        $this->seeInDatabase('posts', [
            'northstar_id' => $northstar_id,
            'campaign_id' => $campaign_id,
            'status' => 'pending',
        ]);
    }
}
