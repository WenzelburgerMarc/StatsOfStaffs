<x-default-layout>
    <div class="w-full py-5">
        <x-form.panel>
            <form action="/admin/manage-employees" method="post" enctype="multipart/form-data" class="flex flex-col">
                @csrf

                <x-form.title>
                    Add Employee
                </x-form.title>

                <x-form.field>
                    <x-form.avatar-input userid="newuser" name="avatar" type="file"/>

                </x-form.field>

                <x-form.field>
                    <x-form.input name="username" type="text" placeholder="Username"
                                  icon="fa-solid fa-at"/>
                </x-form.field>

                <x-form.field>
                    <x-form.input name="name" autocomplete="full-name" type="text" placeholder="Max Mustermann"
                                  icon="fa-solid fa-signature"/>
                </x-form.field>

                <x-form.field>
                    <x-form.input name="email" autocomplete="email" type="email" placeholder="test@mail.com"
                                  icon="fa-solid fa-envelope"/>
                </x-form.field>

                <x-form.field>
                    <x-form.input name="password" autocomplete="current-password" type="password" icon="fa-solid fa-key"
                                  placeholder="Must Have 7 Characters"/>
                </x-form.field>

                @rootadmin
                <x-form.field class="flex w-full items-center justify-center">
                    @php
                        $dropdownRoles = [];

                        foreach ($roles as $role) {
                            $dropdownRoles[] = ['id' => $role->id, 'value' => $role->name];
                        }
                    @endphp
                    @livewire('form.dropdownMenu', ['name'=>'role' ,'title' => 'Filter Role', 'items' => $dropdownRoles, 'selectedId' => 3, 'allOption' => false])

                </x-form.field>
                @endrootadmin

                <x-form.field class="ml-auto">
                    <x-form.checkbox name="isBlocked" labeltext="Is Blocked?"/>
                </x-form.field>

                <x-form.field class="mx-auto">
                    <x-form.primary-button type="submit">Create <i class="ml-1 fa-solid fa-plus"></i>
                    </x-form.primary-button>
                </x-form.field>

            </form>
        </x-form.panel>
    </div>
</x-default-layout>
