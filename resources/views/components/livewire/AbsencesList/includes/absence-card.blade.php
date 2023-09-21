<x-form.panel wire:key="{{$absence->id}}" class="relative absence-card" id="{{'absence' . $absence->id}}">
    @if(isset($absenceEditID) && $absenceEditID == $absence->id)

        @if($absence->user_id == auth()->user()->id && $currentRouteName == 'manage-absences')
            @include('components.livewire.AbsencesList.includes.absence-card-editview')
        @else
            @include('components.livewire.AbsencesList.includes.absence-card-admineditview')
        @endif
    @else
        @include('components.livewire.AbsencesList.includes.absence-card-normal')
    @endif
</x-form.panel>
