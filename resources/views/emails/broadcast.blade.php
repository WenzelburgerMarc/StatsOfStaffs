<x-mail::message>

    # @lang('Hello!')


    @lang($contentMessage)


    @if(!empty($attachment))
        <a href="{{ $attachment }}">Download Attachment</a>
    @endif


    <x-slot:subcopy>
        @lang('Regards'),
        <br/>
        {{auth()->user()->name}}
    </x-slot:subcopy>

</x-mail::message>


