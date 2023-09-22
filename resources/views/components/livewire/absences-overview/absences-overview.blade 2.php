<div class="relative overflow-hidden bg-white py-10 shadow-md dark:bg-slate-200 sm:rounded-lg">
    <div class="flex items-center justify-between bg-white pb-4 dark:bg-slate-200">
        <div class="mx-5 flex flex-col">
            <x-form.title>Absences Overview</x-form.title>
            <span
                class="text-sm text-gray-500">The statistic displays the annual total of days this user was absent.</span>
        </div>
        @php
            $allStatuses = \App\Models\AbsenceStatus::all();
            $statuses = [];
            foreach ($allStatuses as $status) {
                $statuses[] = ['id' => $status->id, 'value' => $status->status];
            }
        @endphp
        <div class="mr-5">
            @livewire('form.dropdownMenu', ['title' => 'Filter Status', 'items' => $statuses, 'selectedId' => $statusSearch, 'emitEvent' => 'absencesOverviewStatusSearchEvent'])
        </div>

    </div>
    <div class="overflow-x-auto">
        <table class="w-full min-w-max text-left text-sm text-gray-500">
            <thead class="bg-slate-100 text-xs uppercase text-gray-700">
            <tr>

                <th scope="col" class="px-6 py-3">
                    All
                </th>
                @foreach($absenceReasons as $absenceReason)
                    <th scope="col" class="px-6 py-3">
                        {{$absenceReason->reason}}
                    </th>

                @endforeach
            </tr>
            </thead>
            <tbody>
            <tr class="bg-white dark:bg-slate-200">

                <td class="px-6 py-4">
                    {{$allDaysOff}}
                </td>
                @foreach($daysOff as $daysOffCount)
                    <td class="px-6 py-4">
                        {{$daysOffCount}}
                    </td>
                @endforeach

            </tr>
            </tbody>
        </table>
    </div>
</div>
