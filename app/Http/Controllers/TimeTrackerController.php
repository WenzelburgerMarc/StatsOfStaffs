<?php

namespace App\Http\Controllers;

use App\Models\User;

class TimeTrackerController extends Controller
{
    public function index(User $username = null)
    {
        $defaultUser = auth()->user();

        if (request()->routeIs('employee-time-tracking')) {
            if (isset($username) && $username->id !== $defaultUser->id) {
                return $this->renderTimeTrackingView($username);
            }

            return redirect()->route('time-tracking');
        }

        return $this->renderTimeTrackingView($username ?? $defaultUser);
    }

    protected function renderTimeTrackingView(User $user)
    {
        return view('staff.time-tracking.index', ['user' => $user]);
    }

    public function getRouteKeyName()
    {
        return 'username';
    }
}
