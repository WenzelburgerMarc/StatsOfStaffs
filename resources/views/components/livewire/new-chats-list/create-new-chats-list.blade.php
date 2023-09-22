<div class="flex flex-col space-y-5 sm:min-w-[280px]">

    @include('components.livewire.new-chats-list.includes.search-card')

    @if($users->isEmpty())
        <div class="flex w-full items-center justify-center">
            <x-form.not-found message="No Users Found"/>
        </div>
    @else
        @foreach($users as $otherUser)
            @include('components.user-preview-card', ['otherUser' => $otherUser, 'action' => 'goToChatRoute'])
        @endforeach

        <div class="w-full pb-5">
            {{ $users->links('vendor.pagination.tailwind') }}
        </div>

    @endif

</div>
