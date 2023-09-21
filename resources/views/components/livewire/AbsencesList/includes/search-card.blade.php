<x-form.panel>
    <x-form.title>
        @if(request()->routeIs('employee-absences'))
            Edit Absences
        @elseif(request()->routeIs('absence'))
            My Absences
        @endif

    </x-form.title>
    <x-form.field>
        <x-form.input
            name="absenceSearchComment"
            type="search"
            icon='fa-solid fa-search'
            :show-label="false"
            :showError="false"
            wire:model.live.debounce.250ms="commentSearch"
            placeholder="Search For Comment"
        />
    </x-form.field>

    <div class="flex items-center justify-center space-x-2">
        @php
            $dropdownReasons = [];
            foreach ($reasons as $reason) {
                $dropdownReasons[] = ['id' => $reason->id, 'value' => $reason->reason];
            }
        @endphp
        <x-form.field>
            @livewire('form.dropdownMenu', ['title' => 'Filter Reason','items' => $dropdownReasons,'selectedId' => $reasonSearch,'emitEvent' => 'absenceReasonSearchEvent'])
        </x-form.field>


        @php
            $dropdownStatuses = [];
            foreach ($statuses as $status) {
                $dropdownStatuses[] = ['id' => $status->id, 'value' => ucwords($status->status)];
            }
        @endphp
        <x-form.field>
            @livewire('form.dropdownMenu', ['title' => 'Filter Status', 'items' => $dropdownStatuses, 'selectedId' => $statusSearch, 'emitEvent' => 'absenceStatusSearchEvent'])
        </x-form.field>


        @php
            $absenceService = new \App\Services\AbsenceService();
            $dateDropdownItems = $absenceService->getDateOptionsWithValues();


        @endphp
        <x-form.field>
            @livewire('form.dropdownMenu', ['title' => 'Filter Date','items' => $dateDropdownItems,'selectedId' => $dateSearch,'emitEvent' => 'absenceDateSearchEvent'])
        </x-form.field>
    </div>

</x-form.panel>
