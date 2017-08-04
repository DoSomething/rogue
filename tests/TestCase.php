<?php

use Illuminate\Http\UploadedFile;
use Rogue\Models\User;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    use RefreshDatabase;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * The Faker generator, for creating test data.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // Refresh database & enable transactions.
        $this->refreshDatabase();

        // Get a new Faker generator from Laravel.
        $this->faker = app(\Faker\Generator::class);
    }

    /**
     * Create an administrator & log them in to the application.
     *
     * @return $this
     */
    public function actingAsAdmin()
    {
        $user = factory(User::class, 'admin')->create();

        return $this->actingAs($user);
    }

    /**
     * Assert that a soft-deleted record exists in the database.
     *
     * @param $table
     * @param $id
     * @return $this
     */
    public function seeSoftDeletedRecord($table, $id)
    {
        $this->seeInDatabase($table, ['id' => $id, 'url' => null])
            ->notSeeInDatabase($table, ['id' => $id, 'deleted_at' => null]);

        return $this;
    }

     * Mock Container dependencies.
     *
     * @param string $class - Class to be mocked.
     *
     * @return \Mockery\MockInterface
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
     * @return UploadedFile
     */
    public function mockFile()
    {
        $path = storage_path('images/huskycorgi.jpeg');
        $original_name = 'huskycorgi.jpeg';
        $mime_type = 'image/jpeg';
        $error = null;
        $test = true;
        return new UploadedFile($path, $original_name, $mime_type, $error, $test);
    }

    /**
     * Set the Rogue API key on the request.
     *
     * @return $this
     */
    public function withRogueApiKey()
    {
        $header = $this->transformHeadersToServerVars(['X-DS-Rogue-API-Key' => env('ROGUE_API_KEY')]);

        $this->serverVariables = array_merge($this->serverVariables, $header);

        return $this;
    }
}
