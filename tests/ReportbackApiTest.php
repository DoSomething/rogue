<?php

use Rogue\Models\Reportback;
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
            'file' => asset('images/huskycorgi.jpeg'),
        ];

        $response = $this->call('POST', $this->reportbackApiUrl, $reportback);

        $this->assertEquals(200, $response->status());

        $response = json_decode($response->content());

        // Make sure we created a reportback item for the reportback.
        $this->seeInDatabase('reportback_items', ['reportback_id' => $response->data->id]);

        // Make sure the file is saved to S3 and the file_url is saved to the database.
        $this->seeInDatabase('reportback_items', ['file_url' => $response->data->reportback_items->data[0]->file_url]);

        // Make sure we created a record in the reportback log table.
        $this->seeInDatabase('reportback_logs', ['reportback_id' => $response->data->id]);
    }

    /**
     * Test if sending a reportback that is already stored throws a 500 error.
     *
     * @return void
     */
    public function testErrorOnDuplicateReportback()
    {
        $reportback = factory(Reportback::class)->create();

        $response = $this->call('POST', $this->reportbackApiUrl, [
            'northstar_id' => $reportback->northstar_id,
            'drupal_id' => $reportback->drupal_id,
            'campaign_id' => $reportback->campaign_id,
            'campaign_run_id' => $reportback->campaign_run_id,
            'quantity' => $this->faker->numberBetween(10, 1000),
            'why_participated' => $this->faker->paragraph(3),
            'num_participated' => null,
            'file_id' => $this->faker->randomNumber(4),
            'caption' => $this->faker->sentence(),
            'source' => 'runscope',
            'remote_addr' => '207.110.19.130',
        ]);

        $this->assertEquals(500, $response->status());
    }
}
