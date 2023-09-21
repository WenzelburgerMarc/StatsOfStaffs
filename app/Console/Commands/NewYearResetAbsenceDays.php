<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class NewYearResetAbsenceDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:new-year-reset-absence-days';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets the remaining absence days of all users to their total absence days.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->remaining_absence_days = $user->total_absence_days;
            $user->save();
        }
        \Log::info('New year reset absence days command executed.');
    }
}
