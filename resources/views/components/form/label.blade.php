@props(['name', 'labeltext' => $name])
<label for="{{$name}}" {{$attributes(['class'=>'"block font-medium text-sm text-gray-700"'])}}>
    {!!  ucwords($labeltext)  !!}
</label>
