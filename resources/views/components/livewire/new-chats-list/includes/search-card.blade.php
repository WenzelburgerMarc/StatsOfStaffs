<x-form.panel>
    <x-form.title>
        Create New Chat
    </x-form.title>
    <x-form.field>
        <x-form.input
            name="chats_search"
            type="search"
            icon='fas fa-search'
            :show-label="false"
            :showError="false"
            wire:model.live.debounce.250ms="search"
            placeholder="Search By Username, Name Or E-Mail"
        />
    </x-form.field>
    <x-form.field class="flex w-full items-center justify-center">
        @php
            $allRoles = \App\Models\Role::all();
            $roles = [];
            foreach ($allRoles as $role) {
                $roles[] = ['id' => $role->id, 'value' => $role->name];
            }
        @endphp
        @livewire('form.dropdownMenu', ['title' => 'Filter Roles', 'items' => $roles, 'selectedId' => $selectedRole, 'emitEvent' => 'newChatsListRoleSearchEvent'])
    </x-form.field>
</x-form.panel>
