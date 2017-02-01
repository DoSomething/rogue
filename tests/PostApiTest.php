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
    protected $postsApiUrl = 'api/v2/posts';

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
        $post = $this->createTestReportback();
        $post['event_type'] = 'post_photo';

        // Mock sending image to AWS.
        Storage::shouldReceive('put')->andReturn(true);

        $response = $this->json('POST', $this->postsApiUrl, $post);

        $this->assertResponseStatus(200);

        // Get the response and make sure we see the right values in the database

        //

        // Make sure we created a reportback item for the reportback.
        // $this->seeInDatabase('events', ['northstar_id' => $response['data']['northstar_id']]);

        // Make sure the file_url is saved to the database.
        // $this->seeInDatabase('reportback_items', ['file_url' => $response['data']['reportback_items']['data'][0]['media']['url']]);

        // // Make sure the edited_file_url is saved to the database.
        // $this->seeInDatabase('reportback_items', ['edited_file_url' => $response['data']['reportback_items']['data'][0]['media']['edited_url']]);

        // // Make sure we created a record in the reportback log table.
        // $this->seeInDatabase('reportback_logs', ['reportback_id' => $response['data']['id']]);
    }
}
