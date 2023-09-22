<x-default-layout>
    <div class="w-full py-5">
        <x-form.panel>
            <form method="POST" action="{{ route('password.update') }}" class="flex flex-col">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <x-form.title>
                    Set New Password
                </x-form.title>

                <x-form.field>
                    <x-form.input icon="" id="email" type="email" name="email" placeholder="Email Address"
                                  value="{{ old('email', $email ?? null) }}" required autocomplete="email"/>
                    @error('email')
                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </x-form.field>

                <x-form.field>
                    <x-form.input icon="" id="password" type="password" name="password" placeholder="New Password"
                                  required autocomplete="new-password"/>
                    @error('password')
                    <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </x-form.field>

                <x-form.field>
                    <x-form.input icon="" id="password-confirm" type="password" name="password_confirmation"
                                  placeholder="Confirm New Password" required autocomplete="new-password"/>
                </x-form.field>

                <x-form.field class="mx-auto">
                    <x-form.primary-button type="submit">{{ __('Reset Password') }}</x-form.primary-button>
                </x-form.field>
            </form>
        </x-form.panel>
    </div>
</x-default-layout>
