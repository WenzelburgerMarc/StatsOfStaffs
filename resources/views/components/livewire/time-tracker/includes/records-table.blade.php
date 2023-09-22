@if($groupedEntries->count() > 0)

    <div class="w-full overflow-x-auto bg-white shadow-md dark:bg-slate-200 md:rounded-xl">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">Description</th>
                <th class="px-4 py-2 text-left">Start Time</th>
                <th class="px-4 py-2 text-left">End Time</th>
                <th class="px-4 py-2 text-left">Duration</th>

                <th class="px-4 py-2 text-left"></th>

            </tr>
            </thead>
            <tbody>
            @foreach($groupedEntries as $groupedEntry)
                @if($groupedEntry['type'] === 'entry')
                    <tr class="{{$loop->last ? '' : 'border-b dark:border-b-gray-500'}}">
                        <td class="px-4 py-2">
                            @if($selectedEditID == $groupedEntry['data']->id)
                                <x-form.input :show-error="false" name="editDescription" :show-label="false"
                                              value="{{$groupedEntry['data']->description}}"
                                              wire:model.live="editDescription" type="text"
                                              class="w-full border border-white"/>
                            @else
                                {{ $groupedEntry['data']->description }}
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($groupedEntry['data']->start_time)->format('H:i:s') }}</td>
                        <td class="px-4 py-2">{{ $groupedEntry['data']->end_time ? \Carbon\Carbon::parse($groupedEntry['data']->end_time)->format('H:i:s') : '-' }}</td>
                        <td class="px-4 py-2 text-left">
                            @if($groupedEntry['data']->end_time)
                                {{ \Carbon\Carbon::parse($groupedEntry['data']->start_time)->diffInHours($groupedEntry['data']->end_time) . 'h ' . (\Carbon\Carbon::parse($groupedEntry['data']->start_time)->diffInMinutes($groupedEntry['data']->end_time) % 60) . 'm ' . (\Carbon\Carbon::parse($groupedEntry['data']->start_time)->diffInSeconds($groupedEntry['data']->end_time) % 60) . 's' }}
                            @else
                                -
                            @endif
                        </td>
                        @if($user->id == auth()->user()->id && $trackingID !== $groupedEntry['data']->id)
                            <td class="px-4 py-2">
                                <div class="flex items-center justify-end space-x-2">

                                    <x-form.icon-button wire:click="restart({{ $groupedEntry['data']->id }})"
                                                        icon="fa-solid fa-play"
                                                        class="text-primary-500 hover:text-primary-600"/>

                                    @if($selectedEditID !== $groupedEntry['data']->id)
                                        <x-form.icon-button wire:click="edit({{ $groupedEntry['data']->id }})"
                                                            icon="fa-solid fa-pen-to-square"
                                                            class="text-green-500 hover:text-green-600"/>

                                    @endif
                                    <x-form.icon-button icon="fa-solid fa-trash"
                                                        wire:click="delete({{ $groupedEntry['data']->id }})"
                                                        class="text-red-500 hover:text-red-600"/>

                                </div>
                            </td>
                        @endif
                    </tr>
                @else
                    <tr wire:click="toggleAccordion('{{ $groupedEntry['description'] }}')"
                        class="cursor-pointer {{ $loop->last ? '' : 'border-b dark:border-b-gray-500'}}">
                        <td class="px-4 py-2">

                            {{ $groupedEntry['description'] }}

                        </td>
                        <td class="px-4 py-2">
                            {{ \Carbon\Carbon::parse($groupedEntry['data']->min('start_time'))->format('H:i:s') }}
                        </td>
                        <td class="px-4 py-2">
                            {{ $groupedEntry['data']->max('end_time') ? \Carbon\Carbon::parse($groupedEntry['data']->max('end_time'))->format('H:i:s') : '-' }}
                        </td>
                        <td class="px-4 py-2 text-left">
                            @php
                                $totalDuration = 0;
                                foreach ($groupedEntry['data'] as $entry) {
                                    if ($entry->end_time) {
                                        $totalDuration += \Carbon\Carbon::parse($entry->start_time)->diffInSeconds($entry->end_time);
                                    }
                                }

                                $hours = floor($totalDuration / 3600);
                                $minutes = floor(($totalDuration / 60) % 60);
                                $seconds = $totalDuration % 60;
                            @endphp

                            {{ "{$hours}h {$minutes}m {$seconds}s" }}
                        </td>
                        <td class="flex items-center justify-end px-4 py-2">
                            <i class="fa-solid fa-chevron-down transition-transform {{$groupedEntry['description'] == $selectedAccordionDescription ? 'rotate-180' : ''}}"></i>
                        </td>
                    </tr>
                    @if($selectedAccordionDescription === $groupedEntry['description'])
                        @foreach($groupedEntry['data'] as $entry)
                            <tr class="{{ $loop->last ? '' : 'border-b dark:border-b-gray-500' }} bg-primary-200">
                                <td class="px-4 py-2">
                                    @if($selectedEditID == $entry->id)
                                        <x-form.input :show-error="false" name="editDescription" :show-label="false"
                                                      value="{{$entry->description}}"
                                                      wire:model.live="editDescription" type="text"
                                                      class="w-full border border-white"/>
                                    @else
                                        {{ $entry->description }}
                                    @endif</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($entry->start_time)->format('H:i:s') }}</td>
                                <td class="px-4 py-2">{{ $entry->end_time ? \Carbon\Carbon::parse($entry->end_time)->format('H:i:s') : '-' }}</td>
                                <td class="px-4 py-2 text-left">
                                    @if($entry->end_time)
                                        {{ \Carbon\Carbon::parse($entry->start_time)->diffInHours($entry->end_time) . 'h ' . (\Carbon\Carbon::parse($entry->start_time)->diffInMinutes($entry->end_time) % 60) . 'm ' . (\Carbon\Carbon::parse($entry->start_time)->diffInSeconds($entry->end_time) % 60) . 's' }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-4 py-2">
                                    @if($user->id == auth()->user()->id && $trackingID !== $entry->id)
                                        <div class="flex items-center justify-end space-x-2">
                                            <x-form.icon-button wire:click="restart({{ $entry->id }})"
                                                                icon="fa-solid fa-play"
                                                                class="text-primary-500 hover:text-primary-600"/>
                                            @if($selectedEditID !== $entry->id)
                                                <x-form.icon-button wire:click="edit({{ $entry->id }})"
                                                                    icon="fa-solid fa-pen-to-square"
                                                                    class="text-green-500 hover:text-green-600"/>
                                            @endif
                                            <x-form.icon-button icon="fa-solid fa-trash"
                                                                wire:click="delete({{ $entry->id }})"
                                                                class="text-red-500 hover:text-red-600"/>
                                        </div>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    @endif

                @endif
            @endforeach

            </tbody>
        </table>
        @if($selectedEditID )
            <div class="flex w-full items-center justify-start p-3 space-x-5">
                <x-form.primary-button wire:click="updateDescription({{$selectedEditID}})">Update
                </x-form.primary-button>
                <button wire:click="cancelEdit()"
                        class="bg-transparent text-gray-600 hover:bg-transparent hover:text-black">
                    Cancel
                </button>
            </div>
        @endif
    </div>

    <div class="flex w-full flex-col space-y-5">
        @include('components.livewire.time-tracker.includes.worked-time-overview')
    </div>

@endif
