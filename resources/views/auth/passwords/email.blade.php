<x-default-layout>
    <div class="w-full py-5">
        <x-form.panel>
            <form method="POST" action="{{ route('password.email') }}" class="flex flex-col">
                @csrf
                <x-form.title>
                    Reset Password
                </x-form.title>

                <x-form.field>

                    <x-form.input icon="" id="email" type="email" name="email" placeholder="Email Address"
                                  value="{{ old('email') }}" required autocomplete="email" autofocus/>

                    @error('email')
                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </x-form.field>

                <x-form.field class="mx-auto">
                    <x-form.primary-button type="submit">{{ __('Send Password Reset Link') }}</x-form.primary-button>
                </x-form.field>
            </form>
        </x-form.panel>
    </div>
</x-default-layout>
