<?php

namespace Tests;

use Mockery;
use Carbon\Carbon;
use Rogue\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    use RefreshDatabase, CreatesApplication;

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

        // Reset mocked time, if set.
        Carbon::setTestNow(null);

        // Fake the storage driver.
        Storage::fake('public');

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
     * "Freeze" time so we can make assertions based on it.
     *
     * @param string $time
     * @return Carbon
     */
    public function mockTime($time = 'now')
    {
        Carbon::setTestNow((string) new Carbon($time));

        return Carbon::getTestNow();
    }

    /**
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
