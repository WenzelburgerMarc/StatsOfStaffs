@props(['name', 'labeltext' => $name])
<div class="form-item-group">
    <x-form.label name="{{$name}}" labeltext="{!! $labeltext  !!}" class="flex-shrink-0"/>
    <select name="{{$name}}" id="{{$name}} {{$attributes}}"
            class="w-full border border-gray-400 p-2" {{$attributes(['value' => old($name)])}}>
        {{ $slot}}
    </select>
    <x-form.error name="{{$name}}"/>
</div>
