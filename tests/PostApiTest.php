<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostApiTest extends TestCase
{
    use DatabaseMigrations;

    /*
     * Base URL for the Api.
     */
    protected $postsApiUrl = '/api/v2/posts';

    /**
     * Test that a POST request to /posts creates a new post
     *
     * @group creatingAPost
     * @return void
     */
    public function testCreatingAPost()
    {
        // Create test Post. Temporarily use the current test reportback data
        // array as the requests are the same.
        // $post = $this->createTestReportback();
        $post = [
            'northstar_id' => 'gibberish',
            'drupal_id' => 6789,
            'campaign_id' => 1234,
            'campaign_run_id' => 567,
            'quantity' => 100,
            'why_participated' => 'Because',
            'num_participated' => NULL,
            'file_id' => 176,
            'caption' => 'olympics',
            'source' => 'runscope',
            'remote_addr' => '207.110.19.130',
        ];

        // Mock sending image to AWS.
        Storage::shouldReceive('put')->andReturn(true);

        $response = $this->json('POST', 'api/v1/reportbacks', $post);

        // $this->assertResponseStatus(200);

        // Get the response and make sure we see the right values in the database
        // $this->checkReportbackResponse($this->decodeResponseJson());
    }
}
