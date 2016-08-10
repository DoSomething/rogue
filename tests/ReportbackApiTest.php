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
        ];

        $response = $this->call('POST', $this->reportbackApiUrl, $reportback);

        $this->assertEquals(200, $response->status());

        $response = json_decode($response->content());

        // Make sure we created a reportback item for the reportback.
        $this->seeInDatabase('reportback_items', ['reportback_id' => $response->data->id]);

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

    /**
    * Test that reportback successfully posts back to Phoenix.
    *
    * @return void
    */
    public function testPostingReportback()
    {
        $reportback = factory(Reportback::class)->create();

        $body = [
            'quantity' => $reportback->quantity,
            'uid' => $reportback->drupal_id,
            'file_url' => $reportback->file,
            'why_participated' => $reportback->why_participated,
            'caption' => $reportback->caption,
            'source' => $reportback->source
        ];

        $response = $this->call('POST', $phoenix->postReportback($reportback->campaign_id, $body));

        $this->assertTrue($response, 'Response is false');
        // $phoenix = new Phoenix();
        // $body = [
        //     'quantity' => 30,
        //     'uid' => 1704953,
        //     'file_url' => 'https://s-media-cache-ak0.pinimg.com/736x/ec/68/65/ec6865940ab8066ef16a41261f2389e1.jpg',
        //     'why_participated' => 'Test',
        //     'caption' => 'Test',
        //     'source' => 'Mobile App'
        // ];

        // $response = $phoenix->postReportback(1631, $body);

    }
}
