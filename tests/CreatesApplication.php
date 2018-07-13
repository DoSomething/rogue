<?php

namespace Tests;

use DoSomething\Gateway\Northstar;
use Illuminate\Contracts\Console\Kernel;
use DoSomething\Gateway\Resources\NorthstarUser;

trait CreatesApplication
{
    /**
     * The Northstar API client mock.
     *
     * @var \Mockery\MockInterface
     */
    protected $northstarMock;

    /**
     * The Faker generator, for creating test data.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $this->app = require __DIR__.'/../bootstrap/app.php';

        $this->app->make(Kernel::class)->bootstrap();

        $this->northstarMock = $this->mock(Northstar::class);
        $this->northstarMock->shouldReceive('asClient')->andReturnSelf();
        $this->northstarMock->shouldReceive('getUser')->andReturnUsing(function ($type, $id) {
            return new NorthstarUser([
                    'id' => $this->faker->northstar_id,
                    'first_name' => $this->faker->firstName,
                    'last_name' => $this->faker->lastName,
                    'birthdate' => $this->faker->date,
                    'email' => $this->faker->email,
                    'mobile' => $this->faker->phoneNumber,
                ]);
        });

        return $this->app;
    }
}
