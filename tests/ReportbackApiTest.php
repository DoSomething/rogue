<?php

use Rogue\Models\Reportback;
use Rogue\Services\Phoenix\Phoenix;
use Faker\Generator;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class ReportbackApiTest extends TestCase
{
    use DatabaseMigrations;

    /*
     * Base URL for the Api.
     */
    protected $reportbackApiUrl = 'api/v1/reportbacks';

    /**
     * Test if a POST request to /reportbacks creates a new reportback.
     *
     * @return void
     */
    public function testCreatingNewReportback()
    {
        // Mock sending image to AWS.
        $this->fileSystem->shouldReceive('put')->andReturn(true);

        // Mock sending reportback back to Phoenix.
        $this->phoenix->shouldReceive('postReportback')->andReturn('12345');

        // Create an uploaded file.
        $file = $this->mockFile();

        $reportback = [
            'northstar_id'     => str_random(24),
            'drupal_id'        => $this->faker->randomNumber(8),
            'campaign_id'      => $this->faker->randomNumber(4),
            'campaign_run_id'  => $this->faker->randomNumber(4),
            'quantity'         => $this->faker->numberBetween(10, 1000),
            'why_participated' => $this->faker->paragraph(3),
            'num_participants' => null,
            'file_id' => $this->faker->randomNumber(4),
            'caption' => $this->faker->sentence(),
            'source' => 'runscope',
            'remote_addr' => '207.110.19.130',
            'file' => $file,
        ];

        $this->json('POST', $this->reportbackApiUrl, $reportback);

        $this->assertResponseStatus(200);

        $response = $this->decodeResponseJson();

        // Make sure we created a reportback item for the reportback.
        $this->seeInDatabase('reportback_items', ['reportback_id' => $response['data']['id']]);

        // Make sure the file is saved to S3 and the file_url is saved to the database.
        $this->seeInDatabase('reportback_items', ['file_url' => $response['data']['reportback_items']['data'][0]['file_url']]);

        // Make sure we created a record in the reportback log table.
        $this->seeInDatabase('reportback_logs', ['reportback_id' => $response['data']['id']]);
    }

    /**
     * Test updating an existing reportback
     *
     * @return void
     */
    public function testUpdatingReportback()
    {
        $reportback = factory(Reportback::class)->create();

        $response = $this->call('POST', $this->reportbackApiUrl, [
            'northstar_id' => $reportback->northstar_id,
            'drupal_id' => $reportback->drupal_id,
            'campaign_id' => $reportback->campaign_id,
            'campaign_run_id' => $reportback->campaign_run_id,
            // Change quanitity.
            'quantity' => 2000,
            'why_participated' => $this->faker->paragraph(3),
            'num_participated' => null,
            'file_id' => $this->faker->randomNumber(4),
            'caption' => $this->faker->sentence(),
            'source' => 'runscope',
            'remote_addr' => '207.110.19.130',
        ]);

        $this->assertResponseStatus(201);

<<<<<<< HEAD
        $this->seeJsonSubset([
            'data' => [
                'quantity' => 2000,
            ],
        ]);
        // $response = json_decode($response->content());
=======
    /**
     * Test that a record is created in the failed log table if Phoenix returns FALSE.
     *
     * @return void
     */
    public function testErrorOnPostToPhoenix()
    {
        // Mock sending image to AWS.
        $this->fileSystem->shouldReceive('put')->andReturn(true);

        // Mock sending reportback back to Phoenix.
        $this->phoenix->shouldReceive('postReportback')->andReturn(FALSE);

        // Create an uploaded file.
        $file = $this->mockFile();

        $reportback = [
            'northstar_id'     => str_random(24),
            'drupal_id'        => $this->faker->randomNumber(8),
            'campaign_id'      => $this->faker->randomNumber(4),
            'campaign_run_id'  => $this->faker->randomNumber(4),
            'quantity'         => $this->faker->numberBetween(10, 1000),
            'why_participated' => $this->faker->paragraph(3),
            'num_participants' => null,
            'file_id' => $this->faker->randomNumber(4),
            'caption' => $this->faker->sentence(),
            'source' => 'runscope',
            'remote_addr' => '207.110.19.130',
            'file' => $file,
        ];

        $this->json('POST', $this->reportbackApiUrl, $reportback);
        $response = $this->decodeResponseJson();

        // Make sure we created a record in the reportback log table.
        $this->seeInDatabase('failed_logs', ['drupal_id' => $response['data']['drupal_id']]);
    }
>>>>>>> adds test to make sure failed post to phoenix gets logged in failed_logs table

        // $this->assertEquals($response->data->quantity, 2000);
    }
}
