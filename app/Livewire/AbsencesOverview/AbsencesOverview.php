<?php

namespace App\Livewire\AbsencesOverview;

use App\Models\AbsenceReason;
use App\services\AbsenceService;
use Livewire\Attributes\On;
use Livewire\Component;

class AbsencesOverview extends Component
{
    public $employee;

    public $statuses;

    public $statusSearch = '';

    public $absenceReasons;

    public $allDaysOff = 0;

    public $daysOff = [];

    private $absenceService;

    public function mount($employee, AbsenceService $absenceService)
    {
        $this->employee = $employee ?? auth()->user()->id;
        $this->absenceReasons = AbsenceReason::all();
        $this->absenceService = $absenceService;
        $this->statuses = $this->absenceService->getStatuses();
    }

    public function render()
    {
        $this->calculateDaysOff();

        return view('components.livewire.absences-overview.absences-overview');
    }

    public function calculateDaysOff()
    {
        $count = 0;
        $absenceService = app(AbsenceService::class);

        foreach ($this->absenceReasons as $absenceReason) {
            $daysOffForReason = $absenceService->calculateDaysOff($this->employee, $absenceReason, (int) $this->statusSearch, true);

            $this->daysOff[$count] = $daysOffForReason;
            $this->allDaysOff += $daysOffForReason;
            $count++;
        }
    }

    #[On('absencesOverviewStatusSearchEvent')]
    public function setStatusSearch($param = '')
    {
        $this->allDaysOff = 0;
        $this->statusSearch = $param === '' ? null : (int) $param;
    }
}
