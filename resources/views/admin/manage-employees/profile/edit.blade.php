<x-default-layout>
    <x-employee-setting :username="$employee->username" class="mb-auto">
        <div class="flex w-full flex-col space-y-5">
            @livewire('absences-overview.absences-overview', ['employee' => $employee])
            <x-staff.edit-form admin-edit="true" username="{{$employee->username}}"/>
        </div>
    </x-employee-setting>
</x-default-layout>
