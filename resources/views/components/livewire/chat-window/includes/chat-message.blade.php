@if($chatMessage->message !== "")
    @if(auth()->user()->id == $chatMessage->sender_id)
        <div class="mb-4 flex justify-end chat-message">
            <div
                class="relative flex w-auto flex-col rounded-tl-3xl rounded-tr-xl rounded-bl-3xl px-4 py-3 text-white bg-primary-400 max-w-[75%] dark:text-black">
                <p class="break-all">{{$chatMessage->message}}</p>
                <span
                    class="ml-auto text-xs text-gray-200 dark:text-gray-800">{{$chatMessage->created_at->diffForHumans()}}</span>
            </div>
            <img src="{{ route('get-file', ['category' => 'avatars', 'filename' => auth()->user()->avatar])}}"
                 class="ml-3 h-8 w-8 rounded-full object-cover" alt="User Image"/>
        </div>
    @else
        <div class="mb-4 flex justify-start chat-message">
            <img src="{{ route('get-file', ['category' => 'avatars', 'filename' => $otherUser->avatar])}}"
                 class="mr-3 h-8 w-8 rounded-full object-cover" alt="Other User Image"/>
            <div
                class="relative flex w-auto flex-col rounded-tl-xl rounded-tr-3xl rounded-br-3xl bg-gray-400 px-4 py-3 text-white max-w-[75%] dark:text-black">
                <p class="break-all">{{$chatMessage->message}}</p>
                <span
                    class="mr-auto text-xs text-gray-200 dark:text-gray-800">{{\Carbon\Carbon::parse($chatMessage->created_at)->diffForHumans()}}</span>
            </div>
        </div>
    @endif
@endif
