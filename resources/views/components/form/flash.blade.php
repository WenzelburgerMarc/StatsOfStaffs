@props(['text', 'status' => 'pending'])
<div x-data="{show: false}"
     x-init="setTimeout(() => show=true, 250); setTimeout(() => show=false, 3000); setTimeout(() => $el.remove(), 4000);"
     class="fixed bottom-5 transition-all duration-1000 opacity-0 right-10"
     x-bind:class="show ? 'translate-x-0 opacity-100' : 'opacity-0 translate-x-full'">
    <x-form.status text="{{$text}}" status="{{$status}}" :fixedWidth="false"/>
</div>


