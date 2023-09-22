<div class="w-full flex flex-col space-y-5">


    @php
        $totalWorkTimeDays = intdiv($totalWorkTime, 86400);
        $totalWorkTimeHours = intdiv($totalWorkTime % 86400, 3600);
        $totalWorkTimeMinutes = intdiv($totalWorkTime % 3600, 60);
        $totalWorkTimeSeconds = $totalWorkTime % 60;
    @endphp

    <table border="1">
        <thead>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Description</th>
            <th>Total Tasks</th>
            <th>Total Work Time</th>
        </tr>
        </thead>
        <tbody>
        @foreach($entries as $entry)
            <tr>
                <td>{{ $entry->id }}</td>
                <td>{{ $entry->user_id }}</td>
                <td>{{ $entry->start_time }}</td>
                <td>{{ $entry->end_time }}</td>
                <td>{{ $entry->description }}</td>
                @if($loop->last)
                    <td>{{ $totalTasksMonth }}</td>
                    <td>{{ $totalWorkTimeDays . 'd ' . $totalWorkTimeHours . 'h ' . $totalWorkTimeMinutes . 'm ' . $totalWorkTimeSeconds . 's' }}</td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
