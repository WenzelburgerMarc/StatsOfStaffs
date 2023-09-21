<?php

namespace App\Http\Controllers;

use App\Exports\ExportAbsences;
use App\Exports\ExportAbsencesForUser;
use App\Exports\ExportAuthedAbsences;
use App\Exports\ExportAuthedUser;
use App\Exports\ExportUser;
use App\Exports\ExportUsers;
use App\Models\User;
use Maatwebsite\Excel\Excel;

class ExportController extends Controller
{
    public function downloadAuthedUserData()
    {
        $excel = app()->make(Excel::class);

        return $excel->download(new ExportAuthedUser(), auth()->user()->username.' - user.csv');
    }

    public function downloadUserData(User $username)
    {
        $excel = app()->make(Excel::class);

        return $excel->download(new ExportUser($username), $username->username.' - user.csv');
    }

    public function downloadAllUserData()
    {
        $excel = app()->make(Excel::class);

        return $excel->download(new ExportUsers(), 'users.csv');
    }

    public function downloadAllAbsencesData()
    {
        $excel = app()->make(Excel::class);

        return $excel->download(new ExportAbsences(), 'absences.csv');
    }

    public function downloadAbsencesForUser(User $username)
    {

        $excel = app()->make(Excel::class);

        return $excel->download(new ExportAbsencesForUser($username), $username->username.' - absences.csv');
    }

    public function downloadAuthedAbsencesData()
    {
        $excel = app()->make(Excel::class);

        return $excel->download(new ExportAuthedAbsences(), auth()->user()->username.' - absences.csv');
    }
}
