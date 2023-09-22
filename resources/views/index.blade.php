<x-default-layout>
    <div class="w-full mt-10 mb-auto flex flex-col justify-center items-center space-y-5">
        <x-form.panel>
            <h1 class="mr-auto break-after-auto text-2xl">Welcome back, {{auth()->user()->name}}</h1>
            <p class="break-after-auto text-base text-gray-500 dark:text-gray-600">It's great to see you.</p>
        </x-form.panel>
        @php
            $chatservice = new \App\Services\ChatService();
            $unreadCount = $chatservice->unreadChatsCount(auth()->user());
        @endphp

        @if($unreadCount > 0)

            <div class="flex w-full items-center justify-center">
                <x-form.status :fixedWidth="false" class="w-full flex-grow"
                               text="You have <strong class='underline'>{{$unreadCount}}</strong> unread message{{$unreadCount > 1 ? 's' : ''}}!"/>
            </div>
        @endif

        @if(auth()->user()->isAdmin())

            @livewire('absences-table')

        @endif

    </div>
</x-default-layout>
