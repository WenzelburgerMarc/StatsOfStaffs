<div class="flex flex-col pt-5 space-y-5 sm:min-w-[280px]">

    @include('components.livewire.AbsencesList.includes.search-card')

    @if($absences->isEmpty())
        <div class="flex w-full items-center justify-center">
            <x-form.not-found message="No Absences Found"/>
        </div>
    @else
        @foreach($absences as $absence)
            @include('components.livewire.AbsencesList.includes.absence-card')
        @endforeach

        <div class="w-full pb-5">
            {{ $absences->links('vendor.pagination.tailwind') }}
        </div>
    @endif


</div>

