<?php

namespace App\Livewire\Staff;

use App\Exports\ExportMonthlyReport;
use App\Models\User;
use App\Models\WorkEntry;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Maatwebsite\Excel\Excel;
use Storage;

class TimeTracker extends Component
{
    public $start_time;

    public $end_time;

    public $description;

    public $user;

    public $tracking = false;

    public $totalWorkTimeDay;

    public $totalTasksDay;

    public $trackingID;

    public $currentDate;

    public $selectedEditID;

    public $editDescription;

    public $totalTasksWeek = 0;

    public $totalWorkTimeWeek = 0;

    public $totalTasksMonth = 0;

    public $totalWorkTimeMonth = 0;

    public $totalTasksYear = 0;

    public $totalWorkTimeYear = 0;

    public $showError = false;

    public $selectedAccordionDescription;

    public $pauseTimeInSeconds = 0;

    protected $listeners = ['refreshTimeTracker' => '$refresh'];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->currentDate = Carbon::today()->format('Y-m-d');
    }

    public function toggleAccordion($description)
    {
        $this->selectedAccordionDescription = $this->selectedAccordionDescription === $description ? null : $description;
    }

    public function changeDay($increment)
    {
        $this->currentDate = Carbon::parse($this->currentDate)->addDays($increment)->format('Y-m-d');
    }

    public function restart($entryID)
    {
        $this->stopTracking($this->trackingID);
        $this->description = WorkEntry::find($entryID)->description;
        $this->startTracking();
    }

    public function stopTracking($entryId)
    {
        if ($this->tracking) {
            $entry = WorkEntry::find($entryId);
            $entry->end_time = Carbon::now();
            $entry->save();

            $this->dispatch('stopTimer');

            $this->tracking = false;

            $this->description = null;

            $this->trackingID = null;
        }

    }

    public function startTracking()
    {
        if ($this->tracking) {
            return;
        }

        if (empty(str_replace(' ', '', $this->description))) {
            $this->showError = true;
            throw ValidationException::withMessages(['description' => 'Please enter a description.']);
        }
        $this->showError = false;
        $entry = WorkEntry::create([
            'user_id' => $this->user->id,
            'start_time' => Carbon::now(),
            'description' => $this->description,
        ]);

        $this->tracking = true;
        $this->trackingID = $entry->id;
        $this->currentDate = Carbon::now()->format('Y-m-d');
        $this->dispatch('startTimer', Carbon::now()->timestamp);
    }

    public function render()
    {
        $entries = WorkEntry::where('user_id', $this->user->id)
            ->whereDate('start_time', $this->currentDate)
            ->orderBy('start_time', 'asc')
            ->get();

        $groupedData = $entries->groupBy('description');

        $groupedEntries = [];
        $addedGroups = [];

        foreach ($entries as $entry) {
            $description = $entry->description;

            if (! in_array($description, $addedGroups)) {
                if (isset($groupedData[$description]) && $groupedData[$description]->count() > 1) {
                    $groupedEntries[] = [
                        'type' => 'group',
                        'description' => $description,
                        'data' => $groupedData[$description],
                    ];
                    $addedGroups[] = $description;
                } else {
                    $groupedEntries[] = [
                        'type' => 'entry',
                        'data' => $entry,
                    ];
                }
            }
        }

        $this->loadTotalStats();

        return view('components.livewire.time-tracker.time-tracker', [
            'groupedEntries' => collect($groupedEntries),
            'pagination' => $entries,

        ]);
    }

    public function loadTotalStats()
    {

        $entriesForTheDay = WorkEntry::where('user_id', $this->user->id)
            ->whereDate('start_time', $this->currentDate)
            ->get();
        $this->totalTasksDay = $entriesForTheDay->unique('description')->count();
        $this->totalWorkTimeDay = $entriesForTheDay->sum(function ($entry) {
            return Carbon::parse($entry->end_time)->diffInSeconds($entry->start_time);
        });

        $entriesForTheWeek = WorkEntry::where('user_id', $this->user->id)
            ->whereBetween('start_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->get();

        $this->totalTasksWeek = $entriesForTheWeek->unique('description')->count();
        $this->totalWorkTimeWeek = $entriesForTheWeek->sum(function ($entry) {
            return Carbon::parse($entry->end_time)->diffInSeconds($entry->start_time);
        });

        $entriesForTheMonth = WorkEntry::where('user_id', $this->user->id)
            ->whereMonth('start_time', Carbon::now()->month)
            ->get();

        $this->totalTasksMonth = $entriesForTheMonth->unique('description')->count();
        $this->totalWorkTimeMonth = $entriesForTheMonth->sum(function ($entry) {
            return Carbon::parse($entry->end_time)->diffInSeconds($entry->start_time);
        });

        $entriesForTheYear = WorkEntry::where('user_id', $this->user->id)
            ->whereYear('start_time', Carbon::now()->year)
            ->get();

        $this->totalTasksYear = $entriesForTheYear->unique('description')->count();
        $this->totalWorkTimeYear = $entriesForTheYear->sum(function ($entry) {
            return Carbon::parse($entry->end_time)->diffInSeconds($entry->start_time);
        });

        $this->calculatePauseTime($entriesForTheDay);
    }

    public function calculatePauseTime($entries): void
    {
        $firstEntry = $entries->first();
        $lastEntry = $entries->last();

        if ($firstEntry && $lastEntry && $firstEntry->start_time && $lastEntry->end_time) {
            $totalPeriodInSeconds = Carbon::parse($firstEntry->start_time)->diffInSeconds($lastEntry->end_time);

            $this->pauseTimeInSeconds = $totalPeriodInSeconds - $this->totalWorkTimeDay;
        } else {
            $this->pauseTimeInSeconds = 0;
        }
    }

    public function delete($entryId)
    {
        try {
            $entry = WorkEntry::find($entryId);
            $entry->delete();
            $this->dispatch('sendFlashMessage', type: 'success', message: 'Entry deleted successfully.');

        } catch (\Error $e) {
            $this->dispatch('sendFlashMessage', type: 'error', message: 'Something went wrong.');
        }

    }

    public function edit($entryId)
    {
        $this->selectedEditID = $entryId;
        $this->editDescription = WorkEntry::find($entryId)->description;
    }

    public function updateDescription($entryId)
    {
        try {
            if (empty(str_replace(' ', '', $this->editDescription))) {
                return $this->dispatch('sendFlashMessage', type: 'error', message: 'Description cannot be empty.');
            }
            $entry = WorkEntry::find($entryId);
            $entry->description = $this->editDescription;
            $entry->save();
            $this->dispatch('sendFlashMessage', type: 'success', message: 'Description updated successfully.');
            $this->cancelEdit();
        } catch (\Error $e) {
            $this->dispatch('sendFlashMessage', type: 'error', message: 'Something went wrong.');
        }

    }

    public function cancelEdit()
    {
        $this->selectedEditID = null;
        $this->editDescription = null;
    }

    public function downloadMonthlyReportCSV()
    {
        $entries = WorkEntry::where('user_id', $this->user->id)
            ->whereMonth('start_time', Carbon::parse($this->currentDate)->month)
            ->get();

        $this->downloadCSV($entries);
    }

    public function downloadCSV($entries): void
    {
        $excel = app()->make(Excel::class);
        $file = $excel->raw(new ExportMonthlyReport($entries, $this->totalWorkTimeMonth, $this->totalTasksMonth), \Maatwebsite\Excel\Excel::CSV);
        $fileName = $this->user->username.'-'.Carbon::parse($this->currentDate)->format('F').'-'.Carbon::parse($this->currentDate)->year.'-report.csv';

        $path = "monthly-reports/{$fileName}";
        Storage::disk('private')->put($path, $file);

        $downloadUrl = route('get-file', ['category' => 'monthly-reports', 'filename' => $fileName]);

        $this->dispatch('downloadMonthlyReport', ['url' => $downloadUrl, 'fileName' => $fileName]);

    }
}
