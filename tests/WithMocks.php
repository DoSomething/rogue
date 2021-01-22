<?php

namespace Tests;

use App\Observers\SignupObserver;
use App\Services\GraphQL;
use Carbon\Carbon;
use DoSomething\Gateway\Northstar;
use DoSomething\Gateway\Resources\NorthstarUser;
use Illuminate\Support\Facades\Storage;

trait WithMocks
{
    /**
     * The Faker instance for the request.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Configure mocks for the application.
     */
    public function configureMocks()
    {
        // Reset mocked time, if set.
        Carbon::setTestNow(null);

        // Fake the storage driver.
        Storage::fake('public');

        // Get a new Faker generator from Laravel.
        $this->faker = app(\Faker\Generator::class);
        $this->faker->addProvider(new \FakerNorthstarId($this->faker));
        $this->faker->addProvider(new \FakerSchoolId($this->faker));

        // Northstar Mock
        $this->northstarMock = $this->mock(Northstar::class);
        $this->northstarMock->shouldReceive('asClient')->andReturnSelf();
        $this->northstarMock->shouldReceive('asUser')->andReturnSelf();
        $this->northstarMock
            ->shouldReceive('refreshIfExpired')
            ->andReturnSelf();
        $this->northstarMock
            ->shouldReceive('getUser')
            ->andReturnUsing(function ($id) {
                return new NorthstarUser([
                    'id' => $id,
                    'first_name' => $this->faker->firstName,
                    'last_name' => $this->faker->lastName,
                    'birthdate' => $this->faker->date,
                    'email' => $this->faker->email,
                    'mobile' => $this->faker->phoneNumber,
                ]);
            });

        // GraphQL Mock
        $this->graphqlMock = $this->mock(GraphQL::class);

        $this->graphqlMock
            ->shouldReceive('getCampaignWebsiteByCampaignId')
            ->andReturn([
                'title' => 'Test Example Campaign',
                'slug' => 'test-example-campaign',
            ]);

        $this->graphqlMock->shouldReceive('getSchoolById')->andReturn([
            'name' => 'San Dimas High School',
        ]);

        $this->graphqlMock->shouldReceive('getUserById')->andReturn([
            'displayName' => 'Daisy D.',
        ]);

        // Signup Observer partial Mock. (To mock the GraphQL query method).
        $this->signupObserverMock = $this->mock(
            SignupObserver::class,
        )->makePartial();
        $this->signupObserverMock->shouldReceive('queryForUser')->andReturn([
            'user' => ['clubId' => null],
        ]);
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
}
