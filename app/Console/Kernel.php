<?php

namespace App\Console;

use Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\NewYearResetAbsenceDays::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        $schedule->command('app:new-year-reset-absence-days')->cron('0-59 0-7 1 1 *');
        $schedule->call(function () {
            Artisan::call('migrate:fresh --seed');

            Artisan::call('session:flush');

            // Delete all files in storage
            $files = glob(storage_path('app/private/*'));
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        })->daily()->between('00:00', '07:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
