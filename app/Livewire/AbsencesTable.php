<?php

namespace App\Livewire;

use App\Models\Absence;
use App\services\AbsenceService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class AbsencesTable extends Component
{
    use withPagination;

    public $absences;

    public $tmpAbsences;

    public $userSearch;

    public $statuses;

    public $statusSearch;

    public $roles;

    public $roleSearch;

    public $dateSearch;

    public $dateOptions;

    protected $absenceService;

    public function __construct()
    {
        $this->absenceService = app(AbsenceService::class);
    }

    public function mount()
    {
        $this->statuses = $this->absenceService->getStatuses();
        $this->roles = $this->absenceService->getRoles();
        $this->dateOptions = $this->absenceService->getDateOptions();
    }

    public function render()
    {
        $this->absences = $this->absenceService->getAbsences($this->statusSearch, $this->roleSearch, $this->userSearch, $this->dateSearch);

        $paginator = $this->absences->paginate(5);
        $this->absences = $paginator->items();

        if ($this->tmpAbsences != $this->absences) {
            $this->tmpAbsences = $this->absences;
            $this->resetPage();
        }

        $paginator->withPath('');

        return view('components.livewire.absences-table', ['paginator' => $paginator]);
    }

    public function deleteAbsence($id)
    {
        try {
            $absence = Absence::find($id);
            $absence->delete();

            $this->dispatch('sendFlashMessage', 'success', 'Absence deleted successfully.');
        } catch (\Error $e) {

            $this->dispatch('sendFlashMessage', 'error', 'Absence could not be deleted.');
        }
    }

    #[On('absencesTableStatusSearchEvent')]
    public function setStatusSearch($status = '')
    {
        $this->statusSearch = $status;
    }

    #[On('absencesTableRoleSearchEvent')]
    public function setRoleSearch($role = '')
    {
        $this->roleSearch = $role;
    }

    #[On('absencesTableDateSearchEvent')]
    public function setDateSearch($date = '')
    {
        $this->dateSearch = $date;
    }
}
