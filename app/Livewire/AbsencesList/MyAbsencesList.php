<?php

namespace App\Livewire\AbsencesList;

use App\Exports\ExportAbsence;
use App\Models\Absence;
use App\Models\AbsenceReason;
use App\Models\AbsenceStatus;
use App\Models\User;
use App\Services\AbsenceService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use Storage;

class MyAbsencesList extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $commentSearch;

    public $tmpCommentSearch;

    public $dateSearch;

    public $tmpDateSearch;

    public $reasonSearch;

    public $tmpReasonSearch;

    public $statusSearch;

    public $tmpStatusSearch;

    public $absenceEditID;

    public $from_date;

    public $from_time;

    public $to_date;

    public $to_time;

    public $reason;

    public $document;

    public $comment;

    public $selectedAbsenceStatus;

    public User $employee;

    public $vacationDays;

    public $currentRouteName;

    public $newRemainingAbsenceDays;

    protected $absenceService;

    protected $listeners = [
        'absenceReasonSearchEvent' => 'updateReasonSearch',
        'absenceStatusSearchEvent' => 'updateStatusSearch',
        'absenceDateSearchEvent' => 'updateDateSearch',
    ];

    public function __construct()
    {
        $this->absenceService = app(AbsenceService::class);
    }

    public function mount(User $employee = null)
    {
        $this->employee = $employee ?? auth()->user();
        $this->currentRouteName = Route::currentRouteName();

    }

    public function render()
    {
        $absences = $this->absenceService->getMyAbsences($this->employee, $this->commentSearch, $this->dateSearch, $this->reasonSearch, $this->statusSearch);
        $paginator = $absences->paginate(3);

        if ($this->employee->id == auth()->user()->id) {
            $paginator->withPath(route('manage-absences'));
        } else {
            $paginator->withPath(route('employee-absences', ['username' => $this->employee->username]));
        }

        return view('components.livewire.AbsencesList.my-absences-list', [
            'absences' => $paginator,
            'reasons' => AbsenceReason::all(),
            'statuses' => AbsenceStatus::all(),
        ]);
    }

    public function edit(Absence $absence)
    {

        $this->absenceEditID = $absence->id;

        $this->from_date = Carbon::parse($absence->start_date)->format('Y-m-d');
        $this->to_date = Carbon::parse($absence->end_date)->format('Y-m-d');
        $this->reason = $absence->absence_reason_id;
        $this->comment = $absence->comment;
        $this->from_time = Carbon::parse($absence->start_date)->format('H:i');
        $this->to_time = Carbon::parse($absence->end_date)->format('H:i');

        $statuses = AbsenceStatus::all();
        foreach ($statuses as $status) {

            if ($absence->status->id == $status->id) {

                $this->selectedAbsenceStatus = $status->id;
            }

        }
        $this->newRemainingAbsenceDays = $this->getNewRemainingAbsenceDaysProperty($absence);
        $this->dispatch('scrollToAbsenceEdit', absenceEditID: $absence->id);
    }

    public function getNewRemainingAbsenceDaysProperty(Absence $absence)
    {

        $startDate = \Carbon\Carbon::parse($this->from_date ?? $absence->start_date);
        $endDate = \Carbon\Carbon::parse($this->to_date ?? $absence->end_date);
        $this->vacationDays = $endDate->diffInDays($startDate) + 1;

        if ($absence->absence_reason_id == 3) {
            if ($this->selectedAbsenceStatus == 2 && $absence->status->id !== 2) {
                return $this->employee->remaining_absence_days - $this->vacationDays;
            } elseif ($this->selectedAbsenceStatus == 2 && $absence->status->id == 2) {
                return $this->employee->remaining_absence_days;
            } elseif ($this->selectedAbsenceStatus !== 2 && $absence->status->id == 2) {
                return $this->employee->remaining_absence_days + $this->vacationDays;
            } else {
                return $this->employee->remaining_absence_days;
            }

        }

        return null;
    }

    #[On('absenceListSetStatus')]
    public function setSelectAbsenceStatus($id)
    {
        $this->selectedAbsenceStatus = $id;
        $this->newRemainingAbsenceDays = $this->getNewRemainingAbsenceDaysProperty(Absence::find($this->absenceEditID));
    }

    #[On('absenceListSetReason')]
    public function setReason($id)
    {
        $this->reason = $id;
    }

    public function delete(Absence $absence)
    {
        try {
            $this->dispatch('sendFlashMessage', 'success', 'Absence Deleted!');
            $this->deleteDocument($absence, true);

            $absence->delete();

        } catch (\Error $e) {

            $this->dispatch('sendFlashMessage', 'error', 'Error during deleting absence.');

        }
    }

    public function deleteDocument(Absence $absence, $hideFlashMessages = false)
    {
        try {
            if (isset($absence->document)) {
                Storage::delete('documents/' . $absence->document);
                $absence->update([
                    'document' => null,
                    'original_document_name' => null,
                ]);
                $this->cancelEdit();
                if (!$hideFlashMessages) {
                    $this->dispatch('sendFlashMessage', 'success', 'Document deleted.');

                }
            } else {
                if (!$hideFlashMessages) {
                    $this->dispatch('sendFlashMessage', 'info', 'No document to delete.');
                }
            }

        } catch (\Error $e) {
            $this->dispatch('sendFlashMessage', 'error', 'Error during deleting document.');
        }

    }

    public function update(Absence $absence)
    {

        $attributes = AbsenceService::getValidatedAttributes([
            'from_date' => $this->from_date,
            'from_time' => $this->from_time,
            'to_date' => $this->to_date,
            'to_time' => $this->to_time,
            'reason' => $this->reason,
            'document' => $this->document,
            'comment' => $this->comment,
        ], $absence);

        if ($this->document && $this->document->isValid() && $this->document != '') {

            $attributes['document'] = $this->document->store('documents');
            $attributes['document'] = substr($attributes['document'], 10);
            $this->document = null;
        } else {

            $attributes['document'] = $absence->document;

        }

        if (Absence::find($this->absenceEditID)->status->status !== 'pending' && !auth()->user()->isAdmin()) {
            $this->dispatch('sendFlashMessage', 'error', 'You can only edit pending absences.');
        } else {
            $absence->update($attributes);

            if (auth()->user()->isAdmin()) {
                $this->updateStatus($absence);
            }

            $this->dispatch('sendFlashMessage', 'success', 'Absence Updated!');
        }

        $this->cancelEdit();

    }

    public function updateStatus(Absence $absence)
    {
        $setNewRemainingAbsenceDays = false;
        if (isset($this->newRemainingAbsenceDays) && is_int($this->newRemainingAbsenceDays) && $this->newRemainingAbsenceDays >= 0 && $this->newRemainingAbsenceDays <= 365) {
            $user = User::find($absence->user_id);

            $user->update([
                'remaining_absence_days' => $this->newRemainingAbsenceDays,
            ]);
            $user->save();
            $setNewRemainingAbsenceDays = true;
        } else {
            if (isset($this->newRemainingAbsenceDays)) {
                $this->dispatch('sendFlashMessage', 'error', 'Remaining absence days must be an integer.');

                return;
            }

        }

        $absence->update([
            'status_id' => $this->selectedAbsenceStatus,
            'approved_at' => now(),
            'approved_by' => auth()->user()->id,
        ]);

        $this->dispatch('sendFlashMessage', 'success', 'Absence Status Updated!');
        $this->cancelEdit();
        if ($setNewRemainingAbsenceDays) {
            $this->dispatch('remainingAbsenceDaysDataUpdated');
        }
    }

    public function cancelEdit()
    {
        $this->absenceEditID = null;
        $this->absenceEditStartDate = null;
        $this->absenceEditEndDate = null;
        $this->absenceEditReasonID = null;
        $this->absenceEditDocument = null;
        $this->absenceEditComment = null;
        $this->absenceEditStartTime = null;
        $this->absenceEditEndTime = null;
    }

    public function updateReasonSearch($selectedId)
    {
        $this->reasonSearch = $selectedId;
    }

    public function updateStatusSearch($selectedId)
    {
        $this->statusSearch = $selectedId;
    }

    public function updateDateSearch($selectedId)
    {
        $this->dateSearch = $selectedId;
    }

    public function downloadCSV(Absence $absence)
    {
        $excel = app()->make(Excel::class);
        $file = $excel->raw(new ExportAbsence($absence), \Maatwebsite\Excel\Excel::CSV);
        $fileName = $absence->id . 'absence.csv';

        $path = "absences/{$fileName}";
        Storage::disk('private')->put($path, $file);

        $downloadUrl = route('get-file', ['category' => 'absences', 'filename' => $fileName]);

        $this->dispatch('downloadAbsence', ['url' => $downloadUrl]);
    }

    private function applyDateSearch($query)
    {
        $today = today();

        switch ($this->dateSearch) {
            case 'earlier':
                $query->whereDate('start_date', '<', $today);
                break;
            case 'today':
                $query->whereDate('start_date', $today);
                break;
            case 'tomorrow':
                $query->whereDate('start_date', $today->copy()->addDay());
                break;
            case 'this_week':
                $query->whereBetween('start_date', [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()]);
                break;
            case 'next_week':
                $query->whereBetween('start_date', [$today->copy()->addWeek()->startOfWeek(), $today->copy()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereBetween('start_date', [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()]);
                break;
            case 'next_month':
                $query->whereBetween('start_date', [$today->copy()->addMonth()->startOfMonth(), $today->copy()->endOfMonth()]);
                break;
            case 'this_year':
                $query->whereBetween('start_date', [$today->copy()->startOfYear(), $today->copy()->endOfYear()]);
                break;
            case 'next_year':
                $query->whereBetween('start_date', [$today->copy()->addYear()->startOfYear(), $today->copy()->endOfYear()]);
                break;
        }
    }
}
