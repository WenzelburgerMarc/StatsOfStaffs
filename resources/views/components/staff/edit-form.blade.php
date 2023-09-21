@props(['adminEdit' => false, 'username' => null])

@php
    $user = \App\Models\User::where('username', $username)->first() ?? auth()->user();
    $roles = \App\Models\Role::all();
@endphp

<x-form.panel>
    @php
        $submitPath = $adminEdit ? '/admin/manage-employees/' . $username . '/profile' : '/staff/edit';

    @endphp
    <form action="{{ $submitPath }}" method="post"
          enctype="multipart/form-data" class="flex flex-col">
        @csrf
        @method('PATCH')


        <div class="flex items-center justify-between">
            <x-form.title>
                Edit Profile
            </x-form.title>

            <x-form.role role="{{$user->role->name}}"/>
        </div>

        <x-form.field>
            <x-form.avatar-input userid="{{$adminEdit ? $user->id : auth()->user()->id }}" name="avatar" type="file"/>
        </x-form.field>

        <x-form.field>
            <x-form.input name="username" type="text" placeholder="Username"
                          icon="fa-solid fa-at"
                          value="{{old('username') ?? $user->username}}"/>
        </x-form.field>

        <x-form.field>
            <x-form.input name="name" autocomplete="full-name" type="text" placeholder="Max Mustermann"
                          icon="fa-solid fa-signature"
                          value="{{old('name') ?? $user->name}}"/>
        </x-form.field>

        <x-form.field>
            <x-form.input name="email" autocomplete="email" type="email" placeholder="test@mail.com"
                          icon="fa-solid fa-envelope"
                          value="{{old('email') ?? $user->email}}"/>
        </x-form.field>

        @if($adminEdit || auth()->user()->isRootAdmin())
            <x-form.field>
                <x-form.input name="total_absence_days" labeltext="Total Vacation Days" type="text"
                              placeholder="{{$user->total_absence_days}}"
                              icon="fa-solid fa-calendar-days"
                              value="{{old('total_absence_days') ?? $user->total_absence_days}}"/>
            </x-form.field>
            <x-form.field>
                <x-form.input name="remaining_absence_days" labeltext="Remaining Vacation Days" type="text"
                              placeholder="{{$user->remaining_absence_days}}"
                              icon="fa-solid fa-calendar-days"
                              value="{{old('remaining_absence_days') ?? $user->remaining_absence_days}}"/>
            </x-form.field>
        @endif

        @if(!$adminEdit)
            <x-form.field>
                <x-form.input name="password" autocomplete="current-password" type="password" icon="fa-solid fa-key"
                              placeholder="Your Current Password"
                              labeltext="Old Password <span class='text-red-600'>*</span>"
                              value="{{old('current-password')}}"/>
            </x-form.field>
        @endif


        <x-form.field>
            <x-form.input name="new-password" labeltext="New Password" autocomplete="new-password"
                          type="password" placeholder="Must Have 7 Characters" value="{{old('new-password')}}"/>
        </x-form.field>

        <x-form.field>
            <x-form.input name="confirm-new-password" labeltext="Confirm New Password"
                          autocomplete="new-password" placeholder="Must Match With The New Password From Above"
                          type="password"/>
        </x-form.field>

        @if($adminEdit)
            <input type="hidden" name="userid" value="{{$user->id}}">

            @php
                $dropdownRoles = [];
                $currentUserRoleId = auth()->user()->role->id;
                $selectedRoleId = $user->role->id;

                foreach ($roles as $role) {
                    if($role->id >= $currentUserRoleId) {
                        $dropdownRoles[] = ['id' => $role->id, 'value' => $role->name];
                    }
                }
            @endphp
            <x-form.field class="flex w-full items-center justify-center">
                @livewire('form.dropdownMenu', ['name'=>'role', 'title' => 'Filter Role', 'items' => $dropdownRoles, 'selectedId' => $selectedRoleId, 'allOption' => false])
            </x-form.field>



            <x-form.field class="ml-auto">
                <x-form.checkbox name="isBlocked" labeltext="Is Blocked?" :value="$user->isBlocked"/>
            </x-form.field>

        @endif
        <x-form.field class="flex items-center justify-center">
            <x-form.primary-button class="mx-auto" type="submit">Update</x-form.primary-button>
        </x-form.field>

    </form>
</x-form.panel>


<x-form.panel class="flex flex-col items-center justify-center space-y-2 sm:space-y-0 sm:space-x-5 sm:flex-row">
    @if($adminEdit)
        <form method="post" action="/admin/manage-employees/{{$user->username}}/profile">
            @csrf
            <x-form.delete-button type="submit" text="User"/>
        </form>
    @endif
    @php
        $submitPathDeleteAvatar = $adminEdit ? '/admin/manage-employees/' . $username . '/profile/avatar' : '/staff/edit/avatar';

    @endphp
    <form method="post" action="{{$submitPathDeleteAvatar}}">
        @csrf
        <x-form.delete-button type="submit" text="Avatar"/>
    </form>
    @php
        $submitDownloadUserCSV = $adminEdit ? '/admin/manage-employees/' . $username . '/profile/download-user-csv' : '/staff/download-user-csv';
    @endphp
    <form method="get" action="{{$submitDownloadUserCSV}}">
        @csrf
        <x-form.primary-button type="submit" text="Download CSV" class="flex items-center justify-center"><i
                class="mr-3 fa-solid fa-download"></i>User Data
        </x-form.primary-button>
    </form>
</x-form.panel>

