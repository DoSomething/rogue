<?php

use Rogue\Models\Reportback;
use Rogue\Models\ReportbackItem;
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
    protected $updateReportbackUrl = 'api/v1/items';

    /**
     * Test if a POST request to /reportbacks creates a new reportback.
     *
     * @return void
     */
    public function testCreatingNewReportback()
    {
        // Create test RB with appropriate emoji
        $reportback = $this->createTestReportback();

        // Test posting the reportback
        $this->postReportback($reportback);

        // Get the response and make sure we see the right values in the database
        $this->checkReportbackResponse($this->decodeResponseJson());
    }

    /**
     * Test updating an existing reportback
     *
     * @return void
     */
    public function testUpdatingReportback()
    {
        // Create a reportback so that one exists
        $reportback = factory(Reportback::class)->create();

        // Change the quantity
        $reportback->quantity = 2000;

        // Post the update
        $this->updateReportback($reportback->toArray());

        // Make sure we see the update in the response
        $this->seeJsonSubset([
            'data' => [
                'quantity' => 2000,
            ],
        ]);
        // $response = json_decode($response->content());

        // $this->assertEquals($response->data->quantity, 2000);
    }

    /**
     * Test updating an existing reportback item
     *
     * @return void
     */
    public function testUpdatingReportbackItem()
    {
        // Create a reportback item and save to the reportback_items table.
        $reportback = factory(Reportback::class)->create();
        $reportbackItem = new ReportbackItem;
        $reportbackItem->reportback_id = $reportback->id;
        $reportbackItem->id = $this->faker->randomNumber(4);
        $reportbackItem->status = 'pending';
        $reportbackItem->save();

        $updatesToReportbackItem = [
            [
                'rogue_reportback_item_id' => $reportbackItem->id,
                'status' => 'approved'
            ]
        ];

        // Post updated status to /items
        $this->call('PUT', $this->updateReportbackUrl, $updatesToReportbackItem);

        $rbItem = ReportbackItem::where(['id' => $reportbackItem->id])->first();

        // Make sure the status is updated to approved.
        $this->assertResponseStatus(201);
        $this->assertEquals('approved', $rbItem->status);

    }

    /**
     * Test posting a reportback item containing an unencoded old emoji
     *
     * @return void
     */
    public function testPostingReportbackWithNormalOldEmoji()
    {
        // Create test RB with appropriate emoji
        $reportback = $this->createTestReportback();
        $reportback['why_participated'] = '🍕';
        $reportback['caption'] = '🐓';

        // Test posting the reportback
        $this->postReportback($reportback);

        // Get the response and make sure we see the right values in the database
        $this->checkReportbackResponse($this->decodeResponseJson());
    }

    /**
     * Test posting a reportback item containing an unencoded ~new~ emoji
     *
     * @return void
     */
    public function testPostingReportbackWithNormalNewEmoji()
    {
        // Create test RB with appropriate emoji
        $reportback = $this->createTestReportback();
        $reportback['why_participated'] = '🍿';
        $reportback['caption'] = '🌮';

        // Test posting the reportback
        $this->postReportback($reportback);

        // Get the response and make sure we see the right values in the database
        $this->checkReportbackResponse($this->decodeResponseJson());
    }

    /**
     * Test posting a reportback item containing an encoded old emoji
     *
     * @return void
     */
    public function testPostingReportbackWithEncodedOldEmoji()
    {
        // Create test RB with appropriate emoji
        $reportback = $this->createTestReportback();
        $reportback['why_participated'] = json_decode("\uD83C\uDF55");
        $reportback['caption'] = json_decode("\uD83D\uDC13");

        // Test posting the reportback
        $this->postReportback($reportback);

        // Get the response and make sure we see the right values in the database
        $this->checkReportbackResponse($this->decodeResponseJson());
    }

    /**
     * Test posting a reportback item containing an encoded new emoji
     *
     * @return void
     */
    public function testPostingReportbackWithEncodedNewEmoji()
    {
        // Create test RB with appropriate emoji
        $reportback = $this->createTestReportback();
        $reportback['why_participated'] = json_decode("\uD83C\uDF2E");
        $reportback['caption'] = json_decode("\uD83C\uDF7F");

        // Test posting the reportback
        $this->postReportback($reportback);

        // Get the response and make sure we see the right values in the database
        $this->checkReportbackResponse($this->decodeResponseJson());
    }
}
