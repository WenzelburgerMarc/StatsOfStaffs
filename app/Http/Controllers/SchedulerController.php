<?php

namespace App\Http\Controllers;

use Artisan;
use Illuminate\Http\Request;

class SchedulerController extends Controller
{
    public function runScheduler(Request $request)
    {

        $username = $request['username'];
        $password = $request['password'];

        $envUsername = config('app.scheduler_username');
        $envPassword = config('app.scheduler_password');


        if ($username === $envUsername && $password === $envPassword) {
            Artisan::call('schedule:run');
            return 'Scheduler executed';
        }

        return response('Unauthorized', 401);
    }
}
