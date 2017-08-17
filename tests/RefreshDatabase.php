<?php

namespace Tests;

use Schema;

trait RefreshDatabase
{
    /**
     * Refresh a conventional test database.
     */
    protected function refreshDatabase()
    {
        if (! RefreshDatabaseState::$migrated) {
            $this->artisan('db:clear');
            $this->artisan('migrate');

            RefreshDatabaseState::$migrated = true;
        }

        // @TODO: Is this necessary for transactions to work...?
        Schema::disableForeignKeyConstraints();

        $this->beginDatabaseTransaction();
    }

    /**
     * Begin a database transaction on the testing database.
     *
     * @return void
     */
    public function beginDatabaseTransaction()
    {
        /** @var \Illuminate\Database\Connection $database */
        $database = $this->app->make('db');

        $database->beginTransaction();

        $this->beforeApplicationDestroyed(function () use ($database) {
            $database->rollBack();
        });
    }
}
