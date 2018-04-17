<?php

namespace Rogue\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\EditImagesCommand::class,
        Commands\SetupCommand::class,
        Commands\ImportSignupsCommand::class,
        Commands\PostQuantity::class,
        Commands\PostCleanup::class,
        Commands\MakeDefaultURLsNull::class,
        Commands\MakeSourceSms::class,
        Commands\TagPosts::class,
        Commands\UpdateSignup::class,
        Commands\ReviewSignup::class,
        Commands\SendToQuasar::class,
        Commands\ForceDeleteTestRecords::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
