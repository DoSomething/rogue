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
        // Mock sending image to AWS.
        $this->fileSystem->shouldReceive('put')->andReturn(true);

        // Mock job that sends reportback back to Phoenix.
        $this->expectsJobs(Rogue\Jobs\SendReportbackToPhoenix::class);

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
        $this->seeInDatabase('reportback_items', ['file_url' => $response['data']['reportback_items']['data'][0]['media']['url']]);

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
        // Mock sending image to AWS.
        $this->fileSystem->shouldReceive('put')->andReturn(true);

        // Mock job that sends reportback back to Phoenix.
        $this->expectsJobs(Rogue\Jobs\SendReportbackToPhoenix::class);

        // Create an uploaded file.
        $file = $this->mockFile();

        $reportback = [
            'northstar_id'     => str_random(24),
            'drupal_id'        => $this->faker->randomNumber(8),
            'campaign_id'      => $this->faker->randomNumber(4),
            'campaign_run_id'  => $this->faker->randomNumber(4),
            'quantity'         => $this->faker->numberBetween(10, 1000),
            'why_participated' => '🍕',
            'num_participants' => $this->faker->optional(0.1)->numberBetween(2, 20),
            'flagged'          => null,
            'flagged_reason'   => null,
            'promoted'         => null,
            'promoted_reason'  => null,
            'file_id'          => $this->faker->randomNumber(4),
            'caption'          => '🐓',
            'source'           => 'runscope',
            'remote_addr'      => '207.110.19.130',
            'file'             => $file,
        ];
        $this->json('POST', $this->reportbackApiUrl, $reportback);

        $this->assertResponseStatus(200);

        $response = $this->decodeResponseJson();

        // Make sure we created a reportback item for the reportback.
        $this->seeInDatabase('reportback_items', ['reportback_id' => $response['data']['id']]);

        // Make sure the file is saved to S3 and the file_url is saved to the database.
        $this->seeInDatabase('reportback_items', ['file_url' => $response['data']['reportback_items']['data'][0]['media']['url']]);

        // Make sure we created a record in the reportback log table.
        $this->seeInDatabase('reportback_logs', ['reportback_id' => $response['data']['id']]);
    }

    /**
     * Test posting a reportback item containing an unencoded ~new~ emoji
     *
     * @return void
     */
    public function testPostingReportbackWithNormalNewEmoji()
    {
        // Mock sending image to AWS.
        $this->fileSystem->shouldReceive('put')->andReturn(true);

        // Mock job that sends reportback back to Phoenix.
        $this->expectsJobs(Rogue\Jobs\SendReportbackToPhoenix::class);

        // Create an uploaded file.
        $file = $this->mockFile();

        $reportback = [
            'northstar_id'     => str_random(24),
            'drupal_id'        => $this->faker->randomNumber(8),
            'campaign_id'      => $this->faker->randomNumber(4),
            'campaign_run_id'  => $this->faker->randomNumber(4),
            'quantity'         => $this->faker->numberBetween(10, 1000),
            'why_participated' => '🍿',
            'num_participants' => $this->faker->optional(0.1)->numberBetween(2, 20),
            'flagged'          => null,
            'flagged_reason'   => null,
            'promoted'         => null,
            'promoted_reason'  => null,
            'file_id'          => $this->faker->randomNumber(4),
            'caption'          => '🌮',
            'source'           => 'runscope',
            'remote_addr'      => '207.110.19.130',
            'file'             => $file,
        ];
        $this->json('POST', $this->reportbackApiUrl, $reportback);

        $this->assertResponseStatus(200);

        $response = $this->decodeResponseJson();

        // Make sure we created a reportback item for the reportback.
        $this->seeInDatabase('reportback_items', ['reportback_id' => $response['data']['id']]);

        // Make sure the file is saved to S3 and the file_url is saved to the database.
        $this->seeInDatabase('reportback_items', ['file_url' => $response['data']['reportback_items']['data'][0]['media']['url']]);

        // Make sure we created a record in the reportback log table.
        $this->seeInDatabase('reportback_logs', ['reportback_id' => $response['data']['id']]);
    }

    /**
     * Test posting a reportback item containing an encoded old emoji
     *
     * @return void
     */
    public function testPostingReportbackWithEncodedOldEmoji()
    {
        // Mock sending image to AWS.
        $this->fileSystem->shouldReceive('put')->andReturn(true);

        // Mock job that sends reportback back to Phoenix.
        $this->expectsJobs(Rogue\Jobs\SendReportbackToPhoenix::class);

        // Create an uploaded file.
        $file = $this->mockFile();

        $reportback = [
            'northstar_id'     => str_random(24),
            'drupal_id'        => $this->faker->randomNumber(8),
            'campaign_id'      => $this->faker->randomNumber(4),
            'campaign_run_id'  => $this->faker->randomNumber(4),
            'quantity'         => $this->faker->numberBetween(10, 1000),
            'why_participated' => json_decode("\uD83C\uDF55"),
            'num_participants' => $this->faker->optional(0.1)->numberBetween(2, 20),
            'flagged'          => null,
            'flagged_reason'   => null,
            'promoted'         => null,
            'promoted_reason'  => null,
            'file_id'          => $this->faker->randomNumber(4),
            'caption'          => json_decode("\uD83D\uDC13"),
            'source'           => 'runscope',
            'remote_addr'      => '207.110.19.130',
            'file'             => $file,
        ];
        $this->json('POST', $this->reportbackApiUrl, $reportback);

        $this->assertResponseStatus(200);

        $response = $this->decodeResponseJson();

        // Make sure we created a reportback item for the reportback.
        $this->seeInDatabase('reportback_items', ['reportback_id' => $response['data']['id']]);

        // Make sure the file is saved to S3 and the file_url is saved to the database.
        $this->seeInDatabase('reportback_items', ['file_url' => $response['data']['reportback_items']['data'][0]['media']['url']]);

        // Make sure we created a record in the reportback log table.
        $this->seeInDatabase('reportback_logs', ['reportback_id' => $response['data']['id']]);
    }

    /**
     * Test posting a reportback item containing an encoded new emoji
     *
     * @return void
     */
    public function testPostingReportbackWithEncodedNewEmoji()
    {
        // Mock sending image to AWS.
        $this->fileSystem->shouldReceive('put')->andReturn(true);

        // Mock job that sends reportback back to Phoenix.
        $this->expectsJobs(Rogue\Jobs\SendReportbackToPhoenix::class);

        // Create an uploaded file.
        $file = $this->mockFile();

        $reportback = [
            'northstar_id'     => str_random(24),
            'drupal_id'        => $this->faker->randomNumber(8),
            'campaign_id'      => $this->faker->randomNumber(4),
            'campaign_run_id'  => $this->faker->randomNumber(4),
            'quantity'         => $this->faker->numberBetween(10, 1000),
            'why_participated' => json_decode("\uD83C\uDF2E"),
            'num_participants' => $this->faker->optional(0.1)->numberBetween(2, 20),
            'flagged'          => null,
            'flagged_reason'   => null,
            'promoted'         => null,
            'promoted_reason'  => null,
            'file_id'          => $this->faker->randomNumber(4),
            'caption'          => json_decode("\uD83C\uDF7F"),
            'source'           => 'runscope',
            'remote_addr'      => '207.110.19.130',
            'file'             => $file,
        ];
        $this->json('POST', $this->reportbackApiUrl, $reportback);

        $this->assertResponseStatus(200);

        $response = $this->decodeResponseJson();

        // Make sure we created a reportback item for the reportback.
        $this->seeInDatabase('reportback_items', ['reportback_id' => $response['data']['id']]);

        // Make sure the file is saved to S3 and the file_url is saved to the database.
        $this->seeInDatabase('reportback_items', ['file_url' => $response['data']['reportback_items']['data'][0]['media']['url']]);

        // Make sure we created a record in the reportback log table.
        $this->seeInDatabase('reportback_logs', ['reportback_id' => $response['data']['id']]);
    }
}
