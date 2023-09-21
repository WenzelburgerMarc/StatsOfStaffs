<x-form.panel class="flex w-full flex-col items-center justify-center">

    <x-form.time-tracking-stats :pause-time-in-seconds="$pauseTimeInSeconds" title="Day" :task-count="$totalTasksDay"
                                :task-work-time="$totalWorkTimeDay"/>

    <x-form.time-tracking-stats title="Week" :task-count="$totalTasksWeek" :task-work-time="$totalWorkTimeWeek"/>

    <x-form.time-tracking-stats title="Month" :task-count="$totalTasksMonth" :task-work-time="$totalWorkTimeMonth"/>

    @if(($user->id !== auth()->user()->id && auth()->user()->isAdmin()) || auth()->user()->isAdmin())
        <x-form.time-tracking-stats :show-days="true" title="Year" :task-count="$totalTasksYear"
                                    :task-work-time="$totalWorkTimeYear"/>

    @endif

</x-form.panel>
