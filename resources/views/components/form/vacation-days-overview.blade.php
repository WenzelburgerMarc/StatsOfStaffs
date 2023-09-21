@props(['employee' => null, 'title' =>'Your Vacation Days'])
<x-form.panel class="flex w-full flex-col items-center space-y-2 justify-content-center">
    <x-form.title>{{$title}}</x-form.title>

    <div class="flex w-full items-center justify-content-between">
        <div class="w-1/2">
            <h2 class="text-xl font-medium">Total</h2>
            <x-form.text>{{ $employee->total_absence_days ?? auth()->user()->total_absence_days}} days</x-form.text>
        </div>
        <div class="w-1/2 text-end">
            <h2 class="text-xl font-medium">Remaining</h2>
            <x-form.text>{{$employee->remaining_absence_days ?? auth()->user()->remaining_absence_days}} days
            </x-form.text>
        </div>
    </div>

    {{$slot}}

</x-form.panel>

<script>
    window.addEventListener('remainingAbsenceDaysDataUpdated', () => {
        location.reload();
    });

</script>
