<x-default-layout>
    <div class="mb-auto w-full">
        @if($user->id !== auth()->user()->id)
            <x-employee-setting :username="$user->username" class="mb-auto">
                <div class="flex w-full flex-col space-y-5">
                    @livewire('staff.time-tracker', ['user' => $user])
                </div>
            </x-employee-setting>
        @else
            @livewire('staff.time-tracker', ['user' => $user])
        @endif
    </div>

</x-default-layout>
