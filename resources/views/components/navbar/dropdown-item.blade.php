@props(['link', 'icon', 'text'])

<a href="{{$link}}"
    {{$attributes->merge(['class' => "w-full px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-primary-100 flex justify-start items-center space-x-2"])}}>
    <div class="flex h-8 w-8 items-center justify-center rounded-full p-1 bg-primary-200 dark:bg-primary-500">
        <i class="{{$icon}}"></i>
    </div>
    <span class="text-gray-700">{{$text}}</span>
</a>
