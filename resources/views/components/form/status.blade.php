@props(['text', 'status' => 'pending', 'showIcon' => true, 'fixedWidth'=>true])

@php
    if($fixedWidth){
        if($showIcon){
            $fixedWidth = 'w-[110px]';
        }else{
            $fixedWidth = 'w-[100px]';
        }
    }else {
        $fixedWidth = 'w-fit';
    }
@endphp

@if($status == 'approved' || $status == 'success')

    <div
        class="flex items-center justify-center p-2 {{$fixedWidth}} text-sm text-green-800 border border-green-300 rounded-lg bg-green-300"
        role="alert">
        <i class="fa-solid fa-circle-info mr-1 {{$showIcon ? '' : 'hidden'}}"></i>
        <div>
            <span class="font-medium w-full">{!! ucfirst($text) !!}</span>
        </div>
    </div>

@elseif($status == 'rejected' || $status == 'error')
    <div
        class="flex items-center justify-center p-2 {{$fixedWidth}} text-sm text-red-800 border border-red-300 rounded-lg bg-red-300"
        role="alert">
        <i class="fa-solid fa-circle-info mr-1 {{$showIcon ? '' : 'hidden'}}"></i>
        <div>
            <span class="font-medium w-full">{!! ucfirst($text) !!}</span>
        </div>
    </div>

@elseif($status == 'pending' || $status == 'info')
    <div
        class="flex items-center justify-center p-2 {{$fixedWidth}} text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-300"
        role="alert">
        <i class="fa-solid fa-circle-info mr-1 {{$showIcon ? '' : 'hidden'}}"></i>
        <div>
            <span class="font-medium w-full">{!! ucfirst($text) !!}</span>
        </div>
    </div>
@endif

