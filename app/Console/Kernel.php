<?php

namespace App\Console;

use App\Jobs\TheGuardianJob;
use App\Jobs\NewsApiJob;
use App\Jobs\NewYorkTimesJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new TheGuardianJob)->daily();
        $schedule->job(new NewsAPIJob)->daily();
        $schedule->job(new NewYorkTimesJob)->daily();
    }
}
