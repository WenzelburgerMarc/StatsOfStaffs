@props(['absence'])
@php
    $user = $absence->user;
@endphp
<tr class="border-b bg-white hover:bg-gray-50 dark:border-b-gray-500 dark:bg-gray-200 dark:text-gray-200 dark:hover:bg-gray-100">

    <th scope="row" class="flex max-w-fit items-center whitespace-nowrap px-3 py-4 text-gray-900 xs:max-w-max lg:px-6">
        <img class="hidden h-10 w-10 rounded-full bg-gray-50 object-cover dark:bg-gray-900 lg:block"
             src="{{ route('get-file', ['category' => 'avatars', 'filename' => $user->avatar])}}"
        >
        <div class="max-h-fit sm:max-w-max sm:pl-3">
            <div
                class="w-32 truncate text-base font-semibold text-black xs:w-auto">{{$user->name}}</div>
            <div
                class="w-32 truncate font-normal text-gray-500 xs:w-auto">{{'@' . $user->username}}</div>
            <div class="w-32 truncate font-normal text-gray-500 xs:w-auto">{{$user->email}}</div>
        </div>
    </th>
    <td class="hidden px-3 py-4 text-black sm:table-cell lg:px-6">
        <div class="flex flex-col">
            <div>
                {{ \Carbon\Carbon::parse($absence->start_date)->format('d.m.Y') }}
            </div>
            <div>
                {{ \Carbon\Carbon::parse($absence->start_date)->format('H:i') }}
            </div>
        </div>
    </td>
    <td class="hidden px-3 py-4 text-black lg:table-cell lg:px-6">
        <div class="flex flex-col">
            <div>
                {{ \Carbon\Carbon::parse($absence->end_date)->format('d.m.Y') }}
            </div>
            <div>
                {{ \Carbon\Carbon::parse($absence->end_date)->format('H:i') }}
            </div>
        </div>
    </td>
    <td class="hidden px-3 py-4 text-black lg:table-cell lg:px-6">
        <div>{{$absence->reason->reason}}</div>
    </td>
    <td class="hidden px-3 py-4 sm:table-cell lg:px-6">
        <div class="flex items-center">

            <x-form.status :show-icon="false" status="{{$absence->status->status}}"
                           text="{{$absence->status->status}}"/>
        </div>
    </td>
    <td class="hidden px-3 py-4 nightwind-prevent-block lg:px-6 xl:table-cell">
        <x-form.role :small="true" role="{{$user->role->name}}"/>

    </td>
    <td class="w-full px-3 py-4 nightwind-prevent-block lg:px-6">

        <div x-data="{show: false}" class="relative w-full">
            <button @click.prevent="show = !show" @click.away="show=false"
                    class="ml-auto flex items-center rounded-lg border border-gray-300 bg-white px-3 text-sm font-medium text-gray-500 py-1.5 hover:bg-gray-100 focus:outline-none dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-primary-900 dark:focus:border-gray-800"
                    type="button">
                Action
                <i class="ml-1 duration-200 fa-solid fa-chevron-down transition-rotate"
                   x-bind:class="show ? 'rotate-180':''"></i>
            </button>
            <div id="dropdownAction" x-cloak x-show="show"
                 class="absolute right-0 z-50 w-44 rounded-lg bg-white shadow divide-y divide-gray-100 dark:bg-gray-900">
                <ul class="text-sm text-gray-700 dark:text-gray-300"
                    aria-labelledby="dropdownActionButton">
                    @php
                        $user = $absence->user;
                        $viewAbsencesLink = '/admin/manage-employees/' . $user->username . '/absences';
                        $manageEmployeeLink = '/admin/manage-employees/' . $user->username . '/profile';
                        if($user->id == auth()->user()->id) {
                           $viewAbsencesLink = '/staff/absence';
                            $manageEmployeeLink = '/staff/edit';
                        }
                    @endphp
                    <li>
                        <a href="{{$viewAbsencesLink}}"
                           class="block rounded-t-lg px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-primary-900">View
                            Absence</a>
                    </li>
                    <li>
                        <a href="#" wire:click="deleteAbsence({{$absence->id}})"
                           class="block px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-primary-900">Delete
                            Absence</a>
                    </li>
                    <li>
                        <a href="{{$manageEmployeeLink}}"
                           class="{{$user->id !== auth()->user()->id ? '' : 'rounded-b-lg'}} block px-4 py-2 hover:bg-gray-100 text-gray-700 dark:hover:bg-primary-900 dark:text-gray-300">Manage
                            Employee</a>
                    </li>
                    @if($user->id !== auth()->user()->id)
                        <li>
                            <a href="/chats/{{$user->username}}"
                               class="block rounded-b-lg px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-primary-900">Chat</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </td>
</tr>
