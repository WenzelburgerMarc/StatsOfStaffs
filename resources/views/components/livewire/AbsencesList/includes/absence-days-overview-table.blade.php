@if($absence->absence_reason_id == 3)
    @if($absence->status_id == 2)
        <div wire:key="{{$absence->id}}" class="w-full rounded-xl bg-gray-100 p-3 shadow-md">
            <table class="w-full">
                <tr>
                    <th class="px-4 py-2 text-left">Description</th>
                    <th class="px-4 py-2 text-right">Days</th>
                </tr>
                <tr>
                    <td class="border-t border-gray-500 px-4 py-2">Total Absence Days</td>
                    <td class="border-t border-gray-500 px-4 py-2 text-right">{{ $employee->total_absence_days }}</td>
                </tr>

                <tr>
                    <td class="border-t border-gray-500 px-4 py-2">Remaining Absence Days With This Absence</td>
                    <td class="border-t border-gray-500 px-4 py-2 text-right">{{ $employee->remaining_absence_days }}</td>
                </tr>
                <tr>
                    <td class="border-t border-gray-500 px-4 py-2">Days Used For This Absence</td>
                    <td class="border-t border-gray-500 px-4 py-2 text-right">{{ $vacationDays }}</td>
                </tr>
                <tr>
                    <td class="border-t border-gray-500 px-4 py-2 font-semibold">Remaining Absence Days After
                        Undoing This
                        Absence
                    </td>
                    <td class="border-t border-gray-500 px-4 py-2 text-right font-semibold">{{ ($employee->remaining_absence_days + $vacationDays) }}</td>
                </tr>
            </table>
        </div>
    @else
        <div class="w-full rounded-xl bg-gray-100 p-3 shadow-md">
            <table class="w-full">
                <tr>
                    <th class="px-4 py-2 text-left">Description</th>
                    <th class="px-4 py-2 text-right">Days</th>
                </tr>
                <tr>
                    <td class="border-t border-gray-500 px-4 py-2">Total Absence Days</td>
                    <td class="border-t border-gray-500 px-4 py-2 text-right">{{ $employee->total_absence_days }}</td>
                </tr>

                <tr>
                    <td class="border-t border-gray-500 px-4 py-2">Remaining Absence Days Before This Absence</td>
                    <td class="border-t border-gray-500 px-4 py-2 text-right">{{ $employee->remaining_absence_days }}</td>
                </tr>
                <tr>
                    <td class="border-t border-gray-500 px-4 py-2">Days Charged If Absence Gets Approved</td>
                    <td class="border-t border-gray-500 px-4 py-2 text-right">{{ $vacationDays }}</td>
                </tr>
                <tr>
                    <td class="border-t border-gray-500 px-4 py-2 font-semibold">Remaining Absence Days After This
                        Absence
                    </td>
                    <td class="border-t border-gray-500 px-4 py-2 text-right font-semibold">{{ ($employee->remaining_absence_days - $vacationDays) }}</td>
                </tr>
            </table>
        </div>
    @endif

    <div class="w-full">
        <x-form.input wire:model.live="newRemainingAbsenceDays" type="text" name="newAbsenceDays"
                      labeltext="New Absence Days Count For User" placeholder="New Absence Days For User"
        />
    </div>

@endif
