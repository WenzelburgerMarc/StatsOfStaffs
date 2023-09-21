<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportMonthlyReport implements FromView
{
    private $entries;

    private $totalWorkTimeMonth;

    private $totalTasksMonth;

    public function __construct($entries, $totalWorkTimeMonth, $totalTasksMonth)
    {
        $this->entries = $entries;
        $this->totalWorkTimeMonth = $totalWorkTimeMonth;
        $this->totalTasksMonth = $totalTasksMonth;
    }

    public function view(): View
    {
        return view('exports.monthly_report', [
            'entries' => $this->entries,
            'totalWorkTime' => $this->totalWorkTimeMonth,
            'totalTasksMonth' => $this->totalTasksMonth,
        ]);
    }
}
