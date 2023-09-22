@props(['link' => $link])
<a href="{{$link}}" target="_blank" {{$attributes(['class'=>'"block font-light text-sm text-gray-700"'])}}>
    {!! $slot !!}
</a>
