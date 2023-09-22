<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SchedulerController extends Controller
{
    public function runScheduler(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $envUsername = config('app.scheduler_username');
        $envPassword = config('app.scheduler_password');

        if ($username === $envUsername && $password === $envPassword) {
            $today = date('Y-m-d');

            if ($today === date('Y') . '-01-01') {
                exec("cd /kunden/homepages/14/d960314401/htdocs/StatsOfStaffs && /usr/bin/php8.1-cli artisan app:new-year-reset-absence-days");
            }

            $output = null;
            $resultCode = null;
            exec("cd /kunden/homepages/14/d960314401/htdocs/StatsOfStaffs && /usr/bin/php8.1-cli artisan migrate:fresh --seed --force", $output, $resultCode);
            session()->flush();

            $this->deleteFiles(storage_path('app/private'));

            return response('Scheduler executed', 200);
        }

        return response('Unauthorized', 401);
    }

    private function deleteFiles($path)
    {
        $files = glob($path . '/*');

        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteFiles($file);
            } elseif (is_file($file) && $file !== storage_path('app/private/avatars/default-avatar.png')) {
                unlink($file);
            }
        }
    }
}
