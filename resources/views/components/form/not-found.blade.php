@props(['message', 'hasShadow' => false])
<div class="max-w-fit p-3 rounded-md flex flex-col items-center justify-center {{$hasShadow ? 'shadow-md' : ''}}">
    <i class="fa-solid fa-triangle-exclamation text-5xl text-gray-700"></i>
    <h3 class="text-2xl font-medium text-gray-700">{{$message}}</h3>
</div>
