<div class="z-50">
    @if(session()->has('success'))
        <x-form.flash
            :text="session('success')" status="success"/>
    @endif

    @if(session()->has('error'))
        <x-form.flash :text="session('error')" status="error"/>
    @endif

    @if(session()->has('info'))
        <x-form.flash :text="session('info')" status="info"/>
    @endif

</div>


