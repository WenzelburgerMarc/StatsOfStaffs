@props(['name', 'labeltext' => $name])
<div class="form-item-group">
    <x-form.label name="{{$name}}" labeltext="{!! $labeltext  !!}" class="flex-shrink-0"/>
    <textarea name="{{$name}}" id="{{$name}}"
              {{$attributes(['class'=>"block w-full rounded-lg border border-gray-300 bg-gray-50 dark:border-0 dark:bg-gray-100 p-2 pl-2 text-sm text-gray-900 resize-none"])}}>{{ old($name) ?? $slot }}</textarea>
    <x-form.error name="{{$name}}"/>
</div>
