<nav
    class="fixed top-0 left-0 z-50 flex h-16 w-screen items-center justify-between bg-white px-6 navbar min-w-[280px] dark:bg-slate-300">

    <div class="logo">
        <a href="/" class="flex items-center justify-center text-base font-medium text-gray-700">
            <i class="text-xl text-gray-700 fa-solid fa-house"></i>
        </a>
    </div>
    @auth
        <div class="flex h-full items-center menu" x-data="{open:false}">
            <ul class="hidden h-full flex-col items-center justify-between text-base font-light text-gray-700 space-x-5 lg:flex lg:flex-row">
                @admin
                <li x-data="{ show: false }" @mouseenter="show = true" @mouseleave="show=false" class="relative h-full">
                    <a href="#" class="flex h-full items-center px-3 text-gray-700">
                        Admin Tools
                        <div :class="{'rotate-180': show}" class="ml-2 transition duration-300">
                            <i class="text-sm fa-solid fa-chevron-down"></i>
                        </div>
                    </a>
                    <div x-cloak x-show="show" @click.away="show = false"
                         class="absolute flex w-auto min-w-max flex-col items-center justify-start rounded-lg bg-white p-3 shadow-lg space-y-1 dark:bg-slate-200">
                        <x-navbar.dropdown-item link="/admin/manage-employees/send-broadcast-mail"
                                                icon="fa-solid fa-envelopes-bulk"
                                                text="Broadcast E-Mail" class="rounded-lg"/>
                        <x-navbar.dropdown-item link="/admin/manage-employees/create" icon="fa-solid fa-plus"
                                                text="Add Employee" class="rounded-lg"/>
                        <x-navbar.dropdown-item link="/admin/manage-employees" icon="fa-solid fa-bars-progress"
                                                text="Manage Employees" class="rounded-lg"/>
                    </div>
                </li>
                @endadmin

                <li>
                    <a href="/chats" class="px-3 py-2">Chats: {{$unreadCount}}</a>
                </li>

                <li x-data="{ show: false }" @mouseenter="show = true" @mouseleave="show=false" class="relative h-full">
                    <button class="flex h-full items-center px-3">
                        <img
                            src="{{ route('get-file', ['category' => 'avatars', 'filename' => auth()->user()->avatar]) }}"
                            alt="user-avatar"
                            class="h-8 w-8 rounded-full bg-transparent object-cover p-0.5">
                        <div :class="{'rotate-180': show}"
                             class="ml-2 text-gray-700 transition duration-300">
                            <i class="text-sm fa-solid fa-chevron-down"></i>
                        </div>


                    </button>
                    <div x-cloak x-show="show" @click.away="show = false"
                         class="absolute right-0 flex w-full min-w-max flex-col items-start justify-start rounded-lg bg-white p-3 shadow-lg space-y-1 dark:bg-slate-200">
                        <x-navbar.dropdown-item link="/staff/edit" icon="fa-solid fa-pen"
                                                text="Edit Account" class="rounded-lg"/>
                        <x-navbar.dropdown-item link="/staff/time-tracking" icon="fa-solid fa-clock"
                                                text="Time Tracker" class="rounded-lg"/>
                        <x-navbar.dropdown-item link="/staff/absence" icon="fa-solid fa-house-medical-flag"
                                                text="My Absences" class="rounded-lg"/>
                        <x-navbar.dropdown-item link="/staff/absence/request" icon="fa-solid fa-code-pull-request"
                                                text="Request Absence" class="rounded-lg"/>
                        <div @click.prevent="document.querySelector('#logout-form').submit()" class="w-full">
                            <x-navbar.dropdown-item link="#"
                                                    icon="fa-solid fa-right-from-bracket"
                                                    text="Log Out" class="rounded-lg"/>
                        </div>
                    </div>
                </li>
            </ul>

            <button @click="open = !open" class="flex items-center lg:hidden">
                <i class="text-2xl fa-solid fa-bars"></i>
            </button>

            <ul x-cloak x-show="open" @click.away="open = false"
                class="absolute top-16 right-1 mt-1 flex w-60 flex-col rounded-lg bg-white shadow-lg dark:bg-gray-200 lg:hidden">
                @admin
                <li class="rounded-t-lg px-4 py-2 font-semibold text-gray-700 bg-primary-200">
                    Admin
                    Tools
                </li>
                <x-navbar.dropdown-item link="/admin/manage-employees/send-broadcast-mail"
                                        icon="fa-solid fa-envelopes-bulk"
                                        text="Broadcast E-Mail"/>
                <x-navbar.dropdown-item link="/admin/manage-employees/create" icon="fa-solid fa-plus"
                                        text="Add Employee"/>
                <x-navbar.dropdown-item link="/admin/manage-employees" icon="fa-solid fa-bars-progress"
                                        text="Manage Employees"/>
                @endadmin
                <li class="px-4 py-2 text-gray-700 font-semibold bg-primary-200 {{ auth()->user()->isAdmin() ? '' : 'rounded-t-lg' }}">
                    Staff Tools
                </li>
                <x-navbar.dropdown-item link="/staff/edit" icon="fa-solid fa-pen"
                                        text="Edit Account"/>
                <x-navbar.dropdown-item link="/staff/time-tracking" icon="fa-solid fa-clock"
                                        text="Time Tracker" class="rounded-lg"/>
                <x-navbar.dropdown-item link="/staff/absence" icon="fa-solid fa-house-medical-flag"
                                        text="My Absences"/>
                <x-navbar.dropdown-item link="/staff/absence/request" icon="fa-solid fa-code-pull-request"
                                        text="Request Absence"/>
                <li class="px-4 py-2 font-semibold text-gray-700 bg-primary-200">
                    Others
                </li>
                <x-navbar.dropdown-item link="/chats" icon="fa-solid fa-comments"
                                        text="Chats: {{$unreadCount}}"/>
                <div @click.prevent="document.querySelector('#logout-form').submit()"
                     class="overflow-hidden rounded-b-xl">
                    <x-navbar.dropdown-item link="#" icon="fa-solid fa-right-from-bracket" text="Log Out"/>
                </div>
            </ul>

            <button id="darkmode-toggler" class="mr-3 ml-10 flex items-center justify-center">
                <i class="text-2xl text-gray-700 fa-solid fa-sun active"></i>
                <i class="text-2xl text-gray-700 fa-solid fa-moon"></i>
            </button>
        </div>


        <form method="POST" action="/logout" id="logout-form"
              class="hidden text-xs font-semibold text-blue-500">
            @csrf
            <button type="submit">Log Out</button>
        </form>
    @endauth
    @guest
        <button id="darkmode-toggler" class="mr-3 ml-auto flex items-center justify-center">
            <i class="text-2xl text-gray-700 fa-solid fa-sun active"></i>
            <i class="text-2xl text-gray-700 fa-solid fa-moon"></i>
        </button>
    @endguest
</nav>


<script type="text/javascript">

    window.addEventListener('scroll', function () {
        let navbar = document.querySelector('.navbar');
        navbar.classList.toggle('shadow', window.scrollY > 0);
    });
</script>
