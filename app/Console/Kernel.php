<?php

namespace App\Console;

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

//        $today = date('Y-m-d');
//
//        if ($today === date('Y') . '-01-01') {
//            $schedule->command('app:new-year-reset-absence-days');
//        }
//        $schedule->call(function () {
//            $output = null;
//            $resultCode = null;
//            exec("/usr/bin/php8.1-cli artisan migrate:fresh --seed", $output, $resultCode);
//            session()->flush();
//
//            $files = glob(storage_path('app/private/*'));
//            foreach ($files as $file) {
//                if (is_file($file) && $file !== storage_path('app/private/avatars/default-avatar.png')) {
//                    unlink($file);
//                }
//            }
//        });
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
