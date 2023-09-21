<x-default-layout class="flex-col">
    <div class="flex w-full flex-col space-y-5">
        <x-form.vacation-days-overview :employee="auth()->user()">
            <div class="flex w-full items-center justify-center">
                <form method="get"
                      action="{{ route('download-user-absences-csv', ['username' => auth()->user()]) }}">
                    @csrf
                    <x-form.primary-button type="submit" class="flex items-center justify-center"><i
                            class="mr-3 fa-solid fa-download"></i>Absences Data
                    </x-form.primary-button>
                </form>

            </div>
        </x-form.vacation-days-overview>
        @livewire('AbsencesList.my-absences-list', ['employee' => auth()->user()])
    </div>
</x-default-layout>
