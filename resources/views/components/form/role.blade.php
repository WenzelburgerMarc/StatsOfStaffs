@props(['role' => 'staff', 'small' => false])

@if($role == 'admin')

    <div
        class="flex items-center justify-center p-2 {{$small ? 'w-[100px]' : ''}} text-sm text-white border border-primary-700 rounded-lg bg-primary-600"
        role="alert">
        @if(!$small)
            <i class="fa-solid fa-user-shield mr-3"></i>
        @endif
        <div>
            <span class="font-medium">{{ucfirst($role)}}</span>
        </div>
    </div>

@elseif($role == 'rootadmin')
    <div
        class="flex items-center justify-center p-2 {{$small ? 'w-[100px]' : ''}} text-sm text-white border border-primary-800 rounded-lg bg-primary-800"
        role="alert">
        @if(!$small)
            <i class="fa-solid fa-user-shield mr-3"></i>
        @endif
        <div>
            <span class="font-medium">{{ucfirst($role)}}</span>
        </div>
    </div>

@elseif($role == 'staff')
    <div
        class="flex items-center justify-center p-2 {{$small ? 'w-[100px]' : ''}} text-sm text-white border border-primary-400 rounded-lg bg-primary-400"
        role="alert">
        @if(!$small)
            <i class="fa-solid fa-user-shield mr-3"></i>
        @endif

        <div>
            <span class="font-medium">{{ucfirst($role)}}</span>
        </div>
    </div>
@endif

