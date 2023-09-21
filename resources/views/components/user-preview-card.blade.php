@props(['otherUser', 'action'])

<x-form.panel class="cursor-pointer" wire:click="{{$action}}('{{$otherUser->username}}')">
    <button class="w-full cursor-pointer text-left focus:outline-none">
        <div class="flex items-center">
            <img class="mr-3 flex-shrink-0 items-start rounded-full bg-gray-300 object-cover w-[50px] h-[50px]"
                 src="{{ route('get-file', ['category' => 'avatars', 'filename' => $otherUser->avatar])}}"
                 width="50"
                 height="50" alt="Marie Zulfikar"/>
            <div class="flex w-full flex-col">
                <div class="flex items-center justify-between">
                    <div class="flex flex-col">
                        <h4 class="flex truncate text-sm font-semibold text-gray-900">
                            <x-form.role-label role="{{$otherUser->role->name}}"/>
                            : {{$otherUser->name}}</h4>
                        <h5 class="truncate text-xs text-gray-500">{{$otherUser->email}}</h5>
                        <h6 class="truncate text-xs text-gray-500">{{'@' . $otherUser->username}}</h6>
                    </div>

                </div>
            </div>
        </div>
    </button>
</x-form.panel>
