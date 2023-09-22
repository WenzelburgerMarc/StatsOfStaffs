@props(['title', 'taskCount', 'taskWorkTime', 'showDays' => false, 'pauseTimeInSeconds' => 0])
@php
    if($showDays) {
        $totalDays = intdiv($taskWorkTime, 86400);
        $totalHours = intdiv($taskWorkTime%86400, 3600);
    }else{
        $totalHours = intdiv($taskWorkTime, 3600);
    }


    $totalMinutes = intdiv($taskWorkTime % 3600, 60);
    $totalSeconds = $taskWorkTime % 60;

    $pauseHours = floor($pauseTimeInSeconds / 3600);
    $pauseMinutes = floor(($pauseTimeInSeconds / 60) % 60);
    $pauseSeconds = $pauseTimeInSeconds % 60;

@endphp
<x-form.field class="flex w-full flex-col items-center justify-center">
    <x-form.title>{{$title}}</x-form.title>
    <div class="flex w-full flex-col sm:space-x-2 sm:flex-row">
        <x-form.field
            class="w-full rounded-full p-3 dark:bg-slate-100 xs:border xs:border-gray-200 xs:shadow-md">
            <div class="flex items-center justify-center space-x-3">
                <div>
                    <i class="text-xl text-gray-700 fa-solid fa-stopwatch-20"></i>
                </div>
                <div class="mr-3 flex flex-col">
                    <x-form.label name="total_tasks" labeltext="Total Tasks"/>

                    @if($taskCount)
                        <x-form.label class="text-gray-500" name="total_tasks_value"
                                      labeltext="{{$taskCount}}"/>
                    @else
                        <x-form.label class="text-gray-500" name="total_tasks_value" labeltext="-"/>
                    @endif
                </div>
            </div>
        </x-form.field>

        <x-form.field
            class="w-full rounded-full p-3 dark:bg-slate-100 xs:border xs:border-gray-200 xs:shadow-md">
            <div class="flex items-center justify-center space-x-3">
                <div>
                    <i class="text-xl text-gray-700 fa-solid fa-hourglass-start"></i>
                </div>
                <div class="mr-3 flex flex-col">
                    <x-form.label name="total_work_time" labeltext="Total Work Time"/>
                    <x-form.label class="text-gray-500" name="total_work_time_value"
                                  labeltext="{{ ($showDays ? $totalDays . 'd ' : '' ) . $totalHours . 'h ' . $totalMinutes . 'm ' . $totalSeconds . 's' }}"/>
                </div>
            </div>
        </x-form.field>
    </div>


    <x-form.field
        class="w-full rounded-full p-3 dark:bg-slate-100 xs:border xs:border-gray-200 xs:shadow-md">
        <div class="flex items-center justify-center space-x-3">
            <div>
                <i class="text-xl text-gray-700 fa-solid fa-hourglass-start"></i>
            </div>
            <div class="mr-3 flex flex-col">
                <x-form.label name="total_pause_time" labeltext="Total Pause Time"/>
                <x-form.label class="text-gray-500" name="total_pause_time_value"
                              labeltext="{{ $pauseHours . 'h ' . $pauseMinutes . 'm ' . $pauseSeconds . 's' }}"/>
            </div>
        </div>
    </x-form.field>
</x-form.field>
