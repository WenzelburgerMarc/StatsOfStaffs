<?php

namespace App\Exports;

use App\Models\Absence;
use App\Models\AbsenceReason;
use App\Models\AbsenceStatus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportAbsencesForUser implements FromCollection, WithHeadings
{
    protected $user;

    public function __construct($username)
    {

        $this->user = $username;

    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $absences = Absence::where('user_id', $this->user->id)->get();

        return collect($absences->map(function ($absence) {
            return $this->map($absence);
        }));

    }

    public function map($absence): array
    {
        $reason = AbsenceReason::find($absence->absence_reason_id)->reason;

        return [
            $absence->id,
            $absence->user_id,
            $absence->start_date,
            $absence->end_date,
            $reason,
            $absence->comment,
            $absence->document,
            $absence->original_document_name,
            AbsenceStatus::find($absence->status_id)->status,
            $absence->approved_by,
            $absence->approved_at,
            $absence->created_at,
            $absence->updated_at,
        ];
    }

    public function headings(): array
    {
        return ['ID', 'User ID', 'Start Date', 'End Date', 'Reason', 'Comment', 'Document', 'Original Document Name', 'Status', 'Approved By', 'Approved At', 'Created At', 'Updated At'];
    }
}
