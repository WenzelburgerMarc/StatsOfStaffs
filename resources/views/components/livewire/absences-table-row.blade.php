@props(['absence'])
@php
    $user = $absence->user;
@endphp
<tr class="border-b dark:border-b-gray-500 bg-white dark:bg-gray-200 dark:hover:bg-gray-100 hover:bg-gray-50 dark:text-gray-200">
    <th scope="row" class="max-w-fit xs:max-w-max flex items-center whitespace-nowrap px-3 lg:px-6 py-4 text-gray-900">
        <img class="h-10 w-10 rounded-full object-cover bg-gray-50 dark:bg-gray-900 hidden lg:block"
             src="{{ route('get-file', ['category' => 'avatars', 'filename' => $absence->user->avatar])}}"
        >
        <div class="sm:pl-3 max-h-fit sm:max-w-max">
            <div
                class="text-base font-semibold w-32 xs:w-auto truncate text-black ">{{$user->name}}</div>
            <div
                class="font-normal text-gray-500 w-32 xs:w-auto truncate ">{{'@' . $user->username}}</div>
            <div class="font-normal text-gray-500 w-32 xs:w-auto truncate ">{{$user->email}}</div>
        </div>
    </th>
    <td class="px-3 lg:px-6 py-4 hidden sm:table-cell text-black">
        <div class="flex flex-col">
            <div>
                {{ \Carbon\Carbon::parse($absence->start_date)->format('d.m.Y') }}
            </div>
            <div>
                {{ \Carbon\Carbon::parse($absence->start_date)->format('H:i') }}
            </div>
        </div>
    </td>
    <td class="px-3 lg:px-6 py-4 hidden lg:table-cell text-black">
        <div class="flex flex-col">
            <div>
                {{ \Carbon\Carbon::parse($absence->end_date)->format('d.m.Y') }}
            </div>
            <div>
                {{ \Carbon\Carbon::parse($absence->end_date)->format('H:i') }}
            </div>
        </div>
    </td>
    <td class="px-3 lg:px-6 py-4 hidden lg:table-cell text-black">
        <div>{{$absence->reason->reason}}</div>
    </td>
    <td class="px-3 lg:px-6 py-4 hidden sm:table-cell">
        <div class="flex items-center">
            <x-form.status :show-icon="false" status="{{$absence->status->status}}"
                           text="{{$absence->status->status}}"/>
        </div>
    </td>
    <td class="px-3 lg:px-6 py-4 hidden xl:table-cell nightwind-prevent-block">
        <x-form.role :small="true" role="{{$user->role->name}}"/>
    </td>
    <td class="px-3 lg:px-6 py-4 w-full nightwind-prevent-block">
        <div x-data="{show: false}" class="relative w-full">
            <button @click.prevent="show = !show" @click.away="show=false"
                    class="ml-auto flex items-center rounded-lg border border-gray-300 bg-white px-3 text-sm font-medium text-gray-500 py-1.5 hover:bg-gray-100 focus:outline-none dark:border-0 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-primary-900 dark:focus:border-gray-950"
                    type="button">
                Action
                <i class="fa-solid fa-chevron-down ml-1 transition-rotate duration-200"
                   x-bind:class="show ? 'rotate-180':''"></i>
            </button>
            <div id="dropdownAction" x-cloak x-show="show"
                 class="absolute z-50 right-0 w-44 rounded-lg bg-white dark:bg-gray-900 shadow divide-y divide-gray-100 ">
                <ul class="text-sm text-gray-700 dark:text-gray-300 "
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
                           class="block rounded-t-lg px-4 py-2 hover:bg-gray-100 text-gray-700 dark:hover:bg-primary-900 dark:text-gray-300">View
                            Absence</a>
                    </li>
                    <li>
                        <a href="#" wire:click="deleteAbsence({{$absence->id}})"
                           class="block px-4 py-2 hover:bg-gray-100 text-gray-700 dark:hover:bg-primary-900 dark:text-gray-300">Delete
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
                               class="rounded-b-lg block px-4 py-2 hover:bg-gray-100 dark:hover:bg-primary-900 text-gray-700 dark:text-gray-300">Chat</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </td>
