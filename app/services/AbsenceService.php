<?php

namespace App\services;

use App\Models\Absence;
use App\Models\AbsenceReason;
use App\Models\AbsenceStatus;
use App\Models\Role;
use App\Models\User;
use App\Rules\SameYear;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Storage;

class AbsenceService
{
    protected $baseDateOptions = [
        'earlier' => 'Earlier',
        'today' => 'Today',
        'tomorrow' => 'Tomorrow',
        'this_week' => 'This Week',
        'next_week' => 'Next Week',
        'this_month' => 'This Month',
        'next_month' => 'Next Month',
        'this_year' => 'This Year',
        'next_year' => 'Next Year',
        'future' => 'In The Future',
    ];

    public static function getValidatedAttributes(array $data, Absence $absence = null, bool $asAdmin = false): array
    {

        $attributes = self::validateAttributes($data, $asAdmin);

        self::validateDateTimes($attributes);

        self::handleDocumentUpload($attributes, $absence);

        return $attributes;
    }

    private static function validateAttributes(array $data, bool $asAdmin): array
    {

        $rules = [
            'from_date' => ['required', 'date'],
            'from_time' => ['required', 'date_format:H:i'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date', ! $asAdmin ? new SameYear($data['from_date']) : ''],
            'to_time' => ['required', 'date_format:H:i'],
            'reason' => ['required', 'exists:absence_reasons,id'],
            'document' => ['nullable', 'file', 'max:1024', 'mimes:pdf,doc,docx,txt,png,svg,jpg,jpeg,rtf'],
            'comment' => ['nullable', 'max:255'],
            'status_id' => ['nullable', 'exists:absence_statuses,id', 'sometimes'],
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {

            throw new ValidationException($validator);
        }

        $attributes = $validator->validated();
        $attributes['start_date'] = $data['from_date'].' '.$data['from_time'];
        $attributes['end_date'] = $data['to_date'].' '.$data['to_time'];
        $attributes['absence_reason_id'] = $attributes['reason'];

        unset($attributes['reason']);

        return $attributes;
    }

    private static function validateDateTimes(array $attributes): void
    {
        $fromDateTime = Carbon::parse($attributes['start_date']);
        $toDateTime = Carbon::parse($attributes['end_date']);

        if ($fromDateTime->gt($toDateTime)) {
            throw ValidationException::withMessages(['to_date' => 'The to date and time must be after the from date and time.']);
        }
    }

    public static function handleDocumentUpload(array &$attributes, Absence $absence = null): void
    {
        if (isset($attributes['document'])) {
            $file = $attributes['document'];
            if (isset($absence)) {
                $currentDocument = $absence->document;

                if (isset($currentDocument)) {
                    if (Storage::exists('documents/'.$absence->document)) {
                        Storage::delete('documents/'.$absence->document);
                    }
                }
            }
            $attributes['original_document_name'] = $file->getClientOriginalName();
            $attributes['document'] = $file->store('documents');
            $attributes['document'] = substr($attributes['document'], 10);

        }
    }

    public function getDateOptions()
    {
        return $this->baseDateOptions;
    }

    public function getDateOptionsWithValues()
    {
        return array_map(function ($key, $value) {
            return ['id' => $key, 'value' => $value];
        }, array_keys($this->baseDateOptions), $this->baseDateOptions);
    }

    public function getStatuses(): object
    {
        return AbsenceStatus::all();
    }

    public function getRoles(): object
    {
        return Role::all();
    }

    public function getMyAbsences(User $employee, ?string $commentSearch, ?string $dateSearch, ?string $reasonSearch, ?string $statusSearch)
    {
        $query = Absence::where('user_id', $employee->id);

        if ($commentSearch) {
            $query->where('comment', 'like', '%'.$commentSearch.'%');
        }

        $this->applyDateSearch($query, $dateSearch);

        if ($reasonSearch) {
            $query->where('absence_reason_id', $reasonSearch);
        }

        if ($statusSearch) {
            $query->where('status_id', $statusSearch);
        }

        return $query->orderBy('start_date', 'asc');
    }

    private function applyDateSearch($query, ?string $dateSearch): void
    {
        if (! $dateSearch) {
            return;
        }

        $today = today();

        switch ($dateSearch) {
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
            case 'future':
                $query->whereDate('start_date', '>=', $today);
                break;
        }
    }

    public function calculateDaysOff(User $employee, AbsenceReason $reason, ?int $statusSearch, bool $onlyThisYear = false): int
    {

        $absences = $employee->absences()->where('absence_reason_id', $reason->id)->get();
        if ($statusSearch) {
            $absences = $absences->where('status_id', $statusSearch);
        }

        $daysOff = 0;
        foreach ($absences as $absence) {
            $startDate = Carbon::parse($absence->start_date);
            $endDate = Carbon::parse($absence->end_date);

            if ($onlyThisYear) {
                $currentYear = Carbon::now()->year;
                $startOfYear = Carbon::create($currentYear, 1, 1);
                $endOfYear = Carbon::create($currentYear, 12, 31);

                if ($startDate->lt($startOfYear)) {
                    $startDate = $startOfYear;
                }

                if ($endDate->gt($endOfYear)) {
                    $endDate = $endOfYear;
                }
            }

            if (! $startDate->gt($endDate)) {
                $daysOff += $startDate->diffInDays($endDate) + 1;
            }
        }

        return $daysOff;
    }

    public function getAbsences(?string $statusSearch, ?string $roleSearch, ?string $userSearch, ?string $dateSearch)
    {
        $query = Absence::query();

        if ($statusSearch) {
            $query->where('status_id', $statusSearch);
        }

        if ($roleSearch) {
            $query->whereHas('user', function ($subQuery) use ($roleSearch) {
                $subQuery->where('role_id', $roleSearch);
            });
        }

        if ($userSearch) {
            $query->whereHas('user', function ($subQuery) use ($userSearch) {
                $subQuery->where('name', 'like', '%'.$userSearch.'%')
                    ->orWhere('email', 'like', '%'.$userSearch.'%')
                    ->orWhere('username', 'like', '%'.$userSearch.'%');
            });
        }

        $this->applyDateSearch($query, $dateSearch);

        return $query->orderByDesc('start_date');
    }
}
