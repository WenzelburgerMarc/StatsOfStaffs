@props(['name', 'disabled' =>false,  'width'=>'w-full', 'labeltext' => $name, 'icon' => '', 'showLabel' => true, 'showError' => true])
<div class="w-full form-item-group nightwind-prevent-block">
    @if($showLabel)
        <x-form.label name="{{$name}}" labeltext="{!! $labeltext  !!}" class="flex-shrink-0"/>
    @endif
    <div class="relative">
        @if($icon)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="{{$icon}} h-4 w-4 text-gray-500"></i>
            </div>
        @endif

        <input name="{{$name}}" id="{{$name}}"
               class="block {{$width}} rounded-lg border border-gray-300 focus:border-gray-300 dark:border-gray-800 dark:focus:border-gray-800 bg-gray-50 dark:bg-gray-800 p-2 @if($icon) pl-10 @else pl-2 @endif text-sm text-gray-900 dark:text-gray-300"
               {{$attributes(['value' => old($name)])}} @if($disabled) disabled @endif >

    </div>
    @if($showError)
        <div class="w-full overflow-hidden">

            <x-form.error name="{{$name}}"/>

        </div>
    @endif


</div>
