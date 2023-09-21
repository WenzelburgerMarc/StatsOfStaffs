<section class="mx-auto w-full max-w-4xl py-8">

    <div class="flex flex-col space-y-2 sm:space-y-0 md:flex-row">
        <aside class="ml-5 w-32 md:ml-0">
            <h4 class="mb-4 font-bold">Links</h4>
            <ul>
                <li>
                    <a href="/chats" class="{{request()->is('chats') ? 'text-primary-600' : ''}}">My Chats</a>
                </li>

                <li>
                    <a href="/chats/create"
                       class="{{request()->is('chats/create') ? 'text-primary-600' : ''}}">New Chat</a>
                </li>
            </ul>
        </aside>

        <main class="flex-1">
            {{$slot}}
        </main>
    </div>


</section>
