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
            'drupal_id' => 12345,
            'campaign_id' => 1234,
            'campaign_run_id' => 567,
            'quantity' => 100,
            'why_participated' => 'Because',
            'num_participated' => 5,
        ];

        $this->json('POST', 'api/v1/reportbacks', $reportback)->assertResponseOk();
    }
}
