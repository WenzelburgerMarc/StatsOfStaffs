<x-form.panel class="w-full">

    <x-form.field class="mb-3 flex items-center justify-center">
        <x-form.primary-button wire:click="downloadMonthlyReportCSV()" class="mx-auto" type="submit">
            <i class="mr-3 fa-solid fa-download"></i>Monthly Report
        </x-form.primary-button>
    </x-form.field>

    @if($user->id == auth()->user()->id)
        <div class="flex w-full flex-col items-end justify-center">
            <div class="flex w-full items-center justify-start space-x-5">
                <x-form.input name="description" :show-error="false" placeholder="What Are You Working On?"
                              width="w-full"
                              type="text"
                              wire:model="description" :disabled="$tracking"/>
                @if(!$tracking)
                    <button
                        class="mt-auto flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-green-600 text-white hover:bg-green-700"
                        wire:click="startTracking"><i class="text-xl fa-solid fa-play"></i>
                    </button>
                @else
                    <button
                        class="mt-auto flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-red-600 text-white hover:bg-red-700"
                        wire:click="stopTracking({{$trackingID}})"><i class="text-xl fa-solid fa-stop"></i>
                    </button>
                @endif

            </div>
            <div class="mr-auto">
                @if($showError)
                    <x-form.error name="description"/>
                @endif
            </div>

            <x-form.title wire:ignore id="timer" class="mx-auto mt-3 text-lg">00:00:00</x-form.title>
        </div>
    @endif
</x-form.panel>


<script>
    window.addEventListener('DOMContentLoaded', () => {

        let timerInterval;

        Livewire.on('startTimer', function (timestamp) {
            clearInterval(timerInterval);

            let start = new Date(timestamp * 1000);

            function updateTimer() {
                let now = new Date();
                let diff = new Date(now - start);
                let hours = diff.getUTCHours().toString().padStart(2, '0');
                let minutes = diff.getUTCMinutes().toString().padStart(2, '0');
                let seconds = diff.getUTCSeconds().toString().padStart(2, '0');

                document.getElementById('timer').innerText = `${hours}:${minutes}:${seconds}`;
            }

            updateTimer();
            timerInterval = setInterval(updateTimer, 1000);
        });


        Livewire.on('stopTimer', function () {
            clearInterval(timerInterval);
            document.getElementById('timer').innerText = '00:00:00';
        });
    });
</script>
