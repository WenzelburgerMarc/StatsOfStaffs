<x-form.field>
    <div class="grid w-full grid-cols-2 gap-4">

        <div class="sm:mr-3">
            <x-form.field>
                <x-form.input class="text-gray-500" :static-error="true" name="from_date"
                              labeltext="From Date"
                              wire:model="from_date" wire:change="getNewRemainingAbsenceDaysProperty({{$absence}})"
                              type="date"/>
            </x-form.field>
        </div>
        <div class="sm:ml-3">
            <x-form.field>
                <x-form.input class="text-gray-500" wire:change="getNewRemainingAbsenceDaysProperty({{$absence}})"
                              :static-error="true" name="to_date" labeltext="To Date"
                              wire:model="to_date"
                              type="date"/>
            </x-form.field>
        </div>


        <div class="sm:mr-3">
            <x-form.field>
                <x-form.input class="text-gray-500" :static-error="true" name="from_time" labeltext="From Time"
                              wire:model="from_time"
                              type="time"/>
            </x-form.field>
        </div>
        <div class="sm:ml-3">
            <x-form.field>
                <x-form.input class="text-gray-500" :static-error="true" name="to_time" labeltext="To Time"
                              wire:model="to_time"
                              type="time"/>
            </x-form.field>
        </div>
    </div>
</x-form.field>

<div class="mt-8">
    <x-form.field class="flex items-center justify-center space-x-5">
        @php
            $dropdownReasons = [];
            foreach ($reasons as $reason) {
                $dropdownReasons[] = ['id' => $reason->id, 'value' => $reason->reason];
            }

            $selectedReason = $absence->absence_reason_id;

        @endphp
        @livewire('form.dropdownMenu', ['title' => '','items' => $dropdownReasons,'selectedId' => $selectedReason, 'emitEvent' => 'absenceListSetReason', 'allOption' => false], key($absence->id))
        @if(auth()->user()->isRootAdmin())
            @php
                $dropdownStatuses = [];

                foreach ($statuses as $status) {
                    $dropdownStatuses[] = ['id' => $status->id, 'value' => $status->status];
                }
            @endphp

            @livewire('form.dropdownMenu', ['title' => 'Set Status', 'items' => $dropdownStatuses, 'selectedId' => $absence->status_id, 'allOption' => false, 'emitEvent' => 'absenceListSetStatus'])
        @endif
    </x-form.field>
</div>

<x-form.field class="flex flex-col">
    <x-form.file-upload type="file" name="document" wire:model="document" :document="$document"/>

    @if($absence->document)

        <div class="mb-2 text-sm font-medium text-gray-700">
            Current File:
            <x-form.link class="text-gray-500 underline"
                         link="{{route('get-file', ['category' => 'documents', 'filename' => $absence->document])}}">
                {{ basename($absence->original_document_name) }}
            </x-form.link>
        </div>

    @endif
</x-form.field>

<x-form.field>
    <div class="flex flex-col">
        <x-form.textarea name="comment" placeholder="Please Provide Additional Information About Your Absence"
                         wire:model="comment"></x-form.textarea>
    </div>
</x-form.field>

@if(auth()->user()->isRootAdmin())
    <x-form.field class="mt-5 flex w-full flex-col items-center justify-center space-y-3">
        @include('components.livewire.AbsencesList.includes.absence-days-overview-table')
    </x-form.field>
@endif

<x-form.field
    class="flex w-full flex-col items-center space-x-5 space-y-2 sm:space-y-0 sm:flex-row sm:justify-start">
    <div class="flex w-full items-center space-x-5 sm:space-y-0 sm:justify-start">
        <x-form.primary-button wire:click="update({{$absence}})">Update</x-form.primary-button>
        <x-form.secondary-button wire:click="cancelEdit()">
            Cancel
        </x-form.secondary-button>
    </div>

    <div class="flex w-full flex-grow items-center justify-end">
        <x-form.delete-button wire:click="deleteDocument({{$absence}})" text="Document"/>
    </div>
</x-form.field>
