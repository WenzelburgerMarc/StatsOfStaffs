<div class="relative block overflow-x-auto overflow-y-visible bg-white shadow-md dark:bg-slate-200 lg:rounded-lg">
    <div
        class="flex flex-col items-center sm:items-start justify-between overflow-y-visible bg-white pb-4 space-y-2 dark:bg-slate-200 sm:space-y-0 sm:flex-row">
        <div
            class="flex flex-col items-center sm:items-start justify-start space-y-2 space-x-0 sm:space-y-0 sm:space-x-2 sm:flex-row mt-3 sm:mt-0">
            @php
                $allStatuses = \App\Models\AbsenceStatus::all();
                $statuses = [];
                foreach ($allStatuses as $status) {
                    $statuses[] = ['id' => $status->id, 'value' => $status->status];
                }
            @endphp
            @livewire('form.dropdownMenu', ['title' => 'Filter Status', 'items' => $statuses, 'selectedId' => $statusSearch, 'emitEvent' => 'absencesTableStatusSearchEvent'])
            @php
                $allRoles = \App\Models\Role::all();
                $roles = [];
                foreach ($allRoles as $role) {
                    $roles[] = ['id' => $role->id, 'value' => $role->name];
                }

            @endphp
            @livewire('form.dropdownMenu', ['title' => 'Filter Roles', 'items' => $roles, 'selectedId' => $roleSearch, 'emitEvent' => 'absencesTableRoleSearchEvent'])

            @php
                $dateDropdownItems = [];

                foreach ($dateOptions as $key => $value) {
                    $dateDropdownItems[] = ['id' => $key, 'value' => $value];


                }

            @endphp
            @livewire('form.dropdownMenu', ['title' => 'Filter Date', 'items' => $dateDropdownItems, 'selectedId' => $dateSearch, 'emitEvent' => 'absencesTableDateSearchEvent'])
        </div>
        <div class="w-full md:ml-5 md:w-auto px-2 sm:px-0 sm:pl-2 md:pl-0">

            <x-form.input
                name="table-search-users"
                icon='fas fa-search'
                :showLabel="false"
                :showError="false"
                wire:model.live.debounce.250ms="userSearch"
                placeholder="Search By Username, Name Or Email"
                width="w-full lg:w-[300px]"


            />


        </div>


    </div>
    @if(isset($absences) && !empty($absences))
        <table class="min-w-full table-auto text-left text-sm text-gray-500">
            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-100">
            <tr>

                <th scope="col" class="w-32 px-3 py-3 xs:w-auto lg:px-6">
                    Name
                </th>
                <th scope="col" class="hidden px-3 py-3 sm:table-cell lg:px-6">
                    From
                </th>
                <th scope="col" class="hidden px-3 py-3 lg:table-cell lg:px-6">
                    To
                </th>
                <th scope="col" class="hidden px-3 py-3 lg:table-cell lg:px-6">
                    Reason
                </th>
                <th scope="col" class="hidden px-3 py-3 sm:table-cell lg:px-6">
                    Status
                </th>
                <th scope="col" class="hidden px-3 py-3 lg:px-6 xl:table-cell">
                    Role
                </th>
                <th scope="col" class="px-3 py-3 lg:px-6">

                </th>
            </tr>
            </thead>
            <tbody>

            @foreach($absences as $absence)
                @include('components.livewire.absences-table-row', ['absence' => $absence])
            @endforeach

            </tbody>
        </table>
    @else
        <div class="flex w-full items-center justify-center">
            <x-form.not-found :has-shadow="false" message="No Absences Found"/>
        </div>

    @endif

    <div class="mt-auto p-4">
        {{ $paginator->links('vendor.pagination.tailwind') }}
    </div>
</div>

