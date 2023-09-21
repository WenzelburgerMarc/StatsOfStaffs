@props(['icon'])
<button
    {{$attributes->merge(['class'=> "bg-transparent px-0 py-0 hover:bg-transparent"])}}>
    <i class="{{$icon}}"></i></button>
