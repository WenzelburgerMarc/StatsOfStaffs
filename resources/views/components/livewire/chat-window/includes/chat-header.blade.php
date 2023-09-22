<x-form.panel class="w-full">
    <a href="javascript:history.back()"><i class="absolute fa-solid fa-arrow-left-long mt-0.5"></i></a>
    <h3 class="mb-3 ml-10 text-base font-semibold">Chatting with:</h3>
    @php
        $otherUserRole = $otherUser->role->id;
        $editUserLink = '';
        if($otherUserRole > auth()->user()->role->id || auth()->user()->isFirstRootAdmin()) {
            $editUserLink = route('manage-employee', $otherUser);

        }else{
            $editUserLink = '#';
        }
    @endphp
    <a href="{{$editUserLink}}"
       class="ml-10 w-full {{$editUserLink !== '#' ? 'cursor-pointer' : 'cursor-default'}} text-left focus:outline-none">
        <div class="flex items-center">
            <img class="mr-3 flex-shrink-0 items-start rounded-full bg-gray-300 w-[50px] h-[50px]"
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
    </a>
</x-form.panel>
