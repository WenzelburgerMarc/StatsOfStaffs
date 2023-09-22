@props(['role' => 'staff'])

@if($role == 'admin')

    <div
        class="w-fit flex items-center text-sm text-primary-600"
        role="alert">
        <div>
            <span class="font-medium">{{ucfirst($role)}}</span>
        </div>
    </div>

@elseif($role == 'rootadmin')
    <div
        class="w-fit flex items-center text-sm text-primary-800"
        role="alert">
        <div>
            <span class="font-medium">{{ucfirst($role)}}</span>
        </div>
    </div>

@elseif($role == 'staff')
    <div
        class="w-fit flex items-center text-sm text-primary-400"
        role="alert">
        <div>
            <span class="font-medium">{{ucfirst($role)}}</span>
        </div>
    </div>
@endif

