<?php

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    protected $reportbackApiUrl = 'api/v1/reportbacks';

    /**
     * The Faker generator, for creating test data.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * File System dependency.
     *
     * @var
     */
    protected $fileSystem;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        // Get a new Faker generator from Laravel.
        $this->faker = app(\Faker\Generator::class);
    }

    /**
     * Mock Container dependencies.
     *
     * @param string $class Class to mock
     *
     * @return void
     */
    public function mock($class)
    {
        $mock = Mockery::mock($class);
        $this->app->instance($class, $mock);
        return $mock;
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Creates a mock file.
     *
     * @return mockfile
     */
    public function mockFile()
    {
        $path = storage_path('images/huskycorgi.jpeg');
        $original_name = 'huskycorgi.jpeg';
        $mime_type = 'image/jpeg';
        $error = null;
        $test = true;
        return new \Illuminate\Http\UploadedFile($path, $original_name, $mime_type, $error, $test);
    }

    /**
     * Creates the Phoenix mock, image, and array with reportback data to use in tests
     *
     * @return array
     */
    public function createTestReportback()
    {

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
            'file_id'          => $this->faker->randomNumber(4),
            'caption'          => $this->faker->sentence(),
            'source'           => 'runscope',
            'remote_addr'      => '207.110.19.130',
            'file'             => $file,
        ];

        return $reportback;
    }

    /**
     * Post a reportback and assert successful response
     *
     * @return array
     */
    public function postReportback($reportback)
    {
        // Mock sending image to AWS.
        Storage::shouldReceive('put')
                    ->andReturn(true);

        $this->json('POST', $this->reportbackApiUrl, $reportback);

        $this->assertResponseStatus(200);
    }

    /**
     * After posting a reportback and receiving a response, make sure we see the expected values in the database
     *
     * @return array
     */
    public function checkReportbackResponse($response)
    {
        // Make sure we created a reportback item for the reportback.
        $this->seeInDatabase('reportback_items', ['reportback_id' => $response['data']['id']]);

        // Make sure the file is saved to S3 and the file_url is saved to the database.
        $this->seeInDatabase('reportback_items', ['file_url' => $response['data']['reportback_items']['data'][0]['media']['url']]);

        // Make sure we created a record in the reportback log table.
        $this->seeInDatabase('reportback_logs', ['reportback_id' => $response['data']['id']]);
    }
}
