<x-default-layout>
    <x-employee-setting :username="$employee->username" class="mb-auto">
        <div class="flex w-full flex-col space-y-5">
            <x-form.vacation-days-overview title="{{$employee->username . ' Vacation Days'}}" :employee="$employee">
                <form method="get"
                      action="{{ route('download-employee-absences-csv', ['username' => $employee->username]) }}">
                    @csrf
                    <x-form.primary-button type="submit" class="flex items-center justify-center"><i
                            class="mr-3 fa-solid fa-download"></i>Absences Data
                    </x-form.primary-button>
                </form>
            </x-form.vacation-days-overview>
            @livewire('absences-overview.absences-overview', ['employee' => $employee])
            @livewire('AbsencesList.my-absences-list', ['employee' => $employee])
        </div>
    </x-employee-setting>


</x-default-layout>
