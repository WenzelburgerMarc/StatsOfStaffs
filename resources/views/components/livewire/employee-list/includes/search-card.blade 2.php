<x-form.panel>
    <x-form.title>
        Manage Employees
    </x-form.title>
    <x-form.field>
        <x-form.input wire:model.live.debounce.250ms="search" type="search" name="chats_search"
                      icon="fa-solid fa-search" :show-label="false"
                      placeholder="Search By Username, Name Or E-Mail"></x-form.input>
    </x-form.field>

    <x-form.field class="flex w-full items-center justify-center">
        @php
            $dropdownRoles = [];
            foreach ($roles as $role) {
                $dropdownRoles[] = ['id' => $role->id, 'value' => $role->name];
            }
        @endphp
        @livewire('form.dropdownMenu', ['title' => 'Filter Role', 'selectedId' => $selectedRole, 'items' => $dropdownRoles, 'emitEvent' => 'manageEmployeesRoleSearchEvent'])

    </x-form.field>
</x-form.panel>
