@props(['name', 'multipleFiles' => false, 'documents' =>null, 'document' =>null, 'width'=>'w-full', 'labeltext' => $name, 'icon' => '', 'showLabel' => true, 'showError' => true])

<div>
    <div class="form-item-group">
        @if($showLabel)
            <x-form.label name="{{$name}}" labeltext="{!! $labeltext  !!}" class="flex-shrink-0"/>
        @endif

        <div class="relative">
            @if($icon)
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="{{$icon}} h-4 w-4 text-gray-500"></i>
                </div>
            @endif

            <input type="file" name="{{$name}}" id="{{$name}}"
                   class="hidden" {{ $multipleFiles ? 'multiple' : '' }} {{ $attributes }}>

            <label for="{{$name}}" type="button" id="{{$name}}_file_btn"
                   class="block {{$width}} text-center rounded-lg border border-gray-300 bg-gray-50 p-2 dark:border-0 dark:bg-gray-100 @if($icon) pl-10 @else pl-2 @endif text-sm text-gray-900 cursor-pointer"
            >
                <i class="mr-2 text-gray-700 fa-solid fa-paperclip"></i>Select a file
            </label>
            @if(isset($document))
                <x-form.text id="{{$name}}_filename" class="mt-1"><strong class="text-sm font-medium">Selected
                        File: </strong>{{$document->getClientOriginalName()}}</x-form.text>
            @endif

            @if(isset($documents) && count($documents) > 0)
                <x-form.text id="{{$name}}_filename" class="mt-1"><strong class="text-sm font-medium">Selected
                        Files: </strong>
                    @foreach($documents as $singleDocument)
                        {{$singleDocument->getClientOriginalName()}}{{end($documents) == $singleDocument ? '' : ', '}}
                    @endforeach</x-form.text>
            @endif
        </div>

        @if($showError)
            <div class="w-full overflow-hidden">
                <x-form.error name="{{$name}}"/>
            </div>
        @endif
    </div>
</div>
