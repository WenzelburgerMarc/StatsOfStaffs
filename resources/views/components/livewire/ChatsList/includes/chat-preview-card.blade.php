@props(['chat'])
@php

    $otherUserID = ($chat->sender_id == auth()->user()->id) ? $chat->receiver_id : $chat->sender_id;
    $otherUser= \App\Models\User::find($otherUserID);

    $latestChat = $chat->getChatService()->latestMessageForUser($otherUser->id);
    $latestChatUser = ($latestChat->sender_id == auth()->user()->id) ? auth()->user() : $otherUser;

@endphp

<x-form.panel class="cursor-pointer max-w-[100vw]" wire:click="goToChatRoute('{{$otherUser->username}}')">
    <button class="w-full cursor-pointer text-left focus:outline-none">
        <div class="flex items-center">
            <img class="mr-3 flex-shrink-0 items-start rounded-full bg-gray-300 object-cover w-[50px] h-[50px]"
                 src="{{ route('get-file', ['category' => 'avatars', 'filename' => $otherUser->avatar])}}"
                 width="50"
                 height="50" alt="Marie Zulfikar"/>
            <div class="flex w-full flex-col">
                <div class="flex items-center justify-between">
                    <div class="flex flex-col">
                        <h4 class="flex overflow-x-hidden truncate text-sm font-semibold text-gray-900">
                            <x-form.role-label role="{{$otherUser->role->name}}"/>
                            : {{$otherUser->name}}</h4>
                        <h5 class="overflow-x-hidden truncate text-xs text-gray-500">{{$otherUser->email}}</h5>
                        <h6 class="overflow-x-hidden truncate text-xs text-gray-500">{{'@' . $otherUser->username}}</h6>
                    </div>

                    @if($chat->read_at == null && $chat->sender_id != auth()->user()->id)
                        <div class="h-2 w-2 rounded-full bg-blue-500"></div>
                    @endif

                </div>
                <div class="grid grid-cols-[1fr,auto] items-center gap-4">
                    <div class="truncate text-xs">
                        {{'@' . $latestChatUser->username . ': ' . $latestChat->message}}
                    </div>

                    <div class="text-right text-xs text-gray-500">
                        {{$latestChat->created_at->diffForHumans()}}
                    </div>
                </div>


            </div>
        </div>
    </button>
</x-form.panel>
