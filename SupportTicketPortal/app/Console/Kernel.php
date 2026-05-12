<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * This schedules the SLA updater to run every minute.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('tickets:update-sla')
            ->everyMinute()        // run every minute
            ->withoutOverlapping(); // prevent overlapping runs
    }

    /**
     * Register the commands for the application.
     *
     * This loads commands from app/Console/Commands
     * and any commands defined in routes/console.php
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
