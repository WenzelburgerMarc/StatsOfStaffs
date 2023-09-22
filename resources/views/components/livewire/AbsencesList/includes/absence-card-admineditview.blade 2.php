@include('components.livewire.AbsencesList.includes.absence-card-normal')
<x-form.field class="flex w-full items-center justify-center">
    @php
        $dropdownStatuses = [];

        foreach ($statuses as $status) {
            $dropdownStatuses[] = ['id' => $status->id, 'value' => $status->status];
        }
    @endphp

    @livewire('form.dropdownMenu', ['title' => 'Set Status', 'items' => $dropdownStatuses, 'selectedId' => $absence->status_id, 'allOption' => false, 'emitEvent' => 'absenceListSetStatus'])

</x-form.field>

<x-form.field class="flex w-full flex-col items-center justify-center space-y-3">

    @include('components.livewire.AbsencesList.includes.absence-days-overview-table')

    <div class="flex w-full items-center justify-start space-x-5">
        <x-form.primary-button wire:click="updateStatus({{$absence}})">Update</x-form.primary-button>
        <button wire:click="cancelEdit()"
                class="bg-transparent text-gray-600 hover:bg-transparent hover:text-black">
            Cancel
        </button>
    </div>


</x-form.field>

