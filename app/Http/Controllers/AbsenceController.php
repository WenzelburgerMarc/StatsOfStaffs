<?php

namespace App\Http\Controllers;

use App\Models\AbsenceReason;
use App\Models\AbsenceStatus;
use App\Models\User;
use App\services\AbsenceService;

class AbsenceController extends Controller
{
    public function index()
    {
        return view('staff.absence.index');
    }

    public function store(User $username = null)
    {

        if (! isset($username)) {
            $attributes = AbsenceService::getValidatedAttributes(request()->all());
            if (auth()->user()->isAdmin()) {
                $attributes['status_id'] = 2;

            }
            auth()->user()->absences()->create($attributes);

            if ($attributes['absence_reason_id'] == 3) {
                return redirect('/staff/absence')->with('success', 'Absence Created! Please edit your vacation days in your Profile.');
            } else {
                return redirect('/staff/absence')->with('success', 'Absence Created!');
            }

        } else {
            $attributes = AbsenceService::getValidatedAttributes(request()->all(), null, true);
            $username->absences()->create($attributes);
            $link = '/admin/manage-employees/'.$username->username.'/absences';

            return redirect($link)->with('success', 'Absence Created!');
        }

    }

    public function create(User $username = null)
    {
        if (request()->routeIs('create-employee-absence')) {
            if (isset($username) && $username->id !== auth()->user()->id) {
                return $this->renderAdminCreateAbsenceView($username);
            }

            return redirect()->route('create-absence');
        }

        return $this->renderCreateAbsenceView();

    }

    protected function renderAdminCreateAbsenceView(User $user)
    {
        return view('admin.manage-employees.absences.create', [
            'reasons' => AbsenceReason::all(),
            'statuses' => AbsenceStatus::all(),
            'user' => $user,
        ]);
    }

    protected function renderCreateAbsenceView()
    {
        return view('staff.absence.create', [
            'reasons' => AbsenceReason::all(),

        ]);
    }
}
