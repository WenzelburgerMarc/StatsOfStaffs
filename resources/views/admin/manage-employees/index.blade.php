<x-default-layout>
    <div class="flex w-full flex-col py-5">
        <x-form.panel class="flex flex-col items-center justify-center space-y-2 sm:space-y-0 sm:space-x-5 sm:flex-row">

            <form method="get" action="/admin/manage-employees/download-users-csv">
                @csrf
                <x-form.primary-button type="submit"
                                       class="flex items-center justify-center"><i
                        class="mr-3 fa-solid fa-download"></i>Users Data
                </x-form.primary-button>
            </form>
            <form method="get" action="/admin/manage-employees/download-absences-csv">
                @csrf
                <x-form.primary-button type="submit"
                                       class="flex items-center justify-center"><i
                        class="mr-3 fa-solid fa-download"></i>Absences Data
                </x-form.primary-button>
            </form>
        </x-form.panel>
        <div class="w-full">
            @livewire('employee-list.employee-list')
        </div>
    </div>


</x-default-layout>
