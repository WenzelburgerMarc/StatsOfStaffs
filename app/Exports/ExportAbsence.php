<?php

namespace App\Exports;

use App\Models\Absence;
use App\Models\AbsenceReason;
use App\Models\AbsenceStatus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportAbsence implements FromCollection, WithHeadings
{
    protected $absence;

    public function __construct(Absence $absence)
    {
        $this->absence = $absence;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect([$this->map($this->absence)]);
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
