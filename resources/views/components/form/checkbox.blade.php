@props(['name', 'labeltext' => $name, 'value' => false])
<div class="inline-flex items-center">
    <input type="checkbox" id="{{$name}}" name="{{$name}}"
           class="appearance-none h-4 w-4 focus:border-0 border border-gray-300 dark:border-gray-700 rounded bg-gray-300 dark:bg-gray-900 dark:checked:bg-primary-200 checked:bg-primary-500"
           @if($value) checked @endif {{$attributes}}>
    <label for="{{$name}}" class="ml-2 dark:text-gray-700">{{ $labeltext }}</label>
</div>
