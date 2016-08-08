<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReportbackApiTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test if a POST request to /reportbacks creates a new reportback.
     *
     * @return void
     */
    public function testCreatingNewReportback()
    {
        // @TODO - use faker.
        // @TODO - test errors.
        $reportback = [
            'northstar_id' => 'gibberish',
            'drupal_id' => '12345',
            'campaign_id' => '1234',
            'campaign_run_id' => '567',
            'quantity' => 100,
            'why_participated' => 'Because I rock',
            'num_participated' => NULL,
            'file_id' => '176',
            'caption' => 'olympic gold',
            'source' => 'runscope',
            'remote_addr' => '207.110.19.130',
        ];

        $response = $this->call('POST', 'api/v1/reportbacks', $reportback);

        $this->assertEquals(200, $response->status());

        $response = json_decode($response->content());

        // Make sure we created a reportback item for the reportback.
        $this->seeInDatabase('reportback_items', ['reportback_id' => $response->data->id]);

        // Make sure we created a record in the reportback log table.
        $this->seeInDatabase('reportback_logs', ['reportback_id' => $response->data->id]);
    }
}
