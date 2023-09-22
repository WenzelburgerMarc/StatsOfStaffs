@props(['username'])
<section class="mx-auto w-full max-w-4xl py-8">

    <div class="flex flex-col space-y-2 sm:space-y-0 lg:flex-row">
        <aside class="ml-5 w-32 flex-shrink-0 lg:ml-0">
            <h4 class="mb-4 font-bold">Links</h4>
            <ul>
                <li>
                    <a href="/admin/manage-employees">All Employees</a>
                </li>
                <li>
                    <a href="/admin/manage-employees/{{$username}}/profile"
                       class="{{request()->routeIs('manage-employee') ? 'text-primary-600' : ''}}">Profile</a>
                </li>
                <li>
                    <a href="/admin/manage-employees/{{$username}}/time-tracking"
                       class="{{request()->routeIs('employee-time-tracking') ? 'text-primary-600' : ''}}">Time
                        Tracker</a>
                </li>

                <li>
                    <a href="/admin/manage-employees/{{$username}}/absences"
                       class="{{request()->routeIs('employee-absences') ? 'text-primary-600' : ''}}">Absences</a>
                </li>
                <li>
                    <a href="/admin/manage-employees/{{$username}}/absence/create"
                       class="{{request()->routeIs('create-employee-absence') ? 'text-primary-600' : ''}}">Create
                        Absence
                    </a>
                </li>
            </ul>
        </aside>

        <main class="w-full">
            {{$slot}}
        </main>
    </div>


</section>

