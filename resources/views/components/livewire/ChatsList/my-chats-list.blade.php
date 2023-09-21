<div class="flex flex-col space-y-5 sm:min-w-[280px]">

    @include('components.livewire.ChatsList.includes.search-card')

    @if($chats->isEmpty())
        <div class="flex w-full items-center justify-center">
            <x-form.not-found message="No Chats Found"/>
        </div>
    @else
        @foreach($chats as $chat)
            @include('components.livewire.ChatsList.includes.chat-preview-card', ['chat' => $chat])
        @endforeach

        <div class="w-full pb-5">
            {{ $chats->links('vendor.pagination.tailwind') }}
        </div>

    @endif

</div>
