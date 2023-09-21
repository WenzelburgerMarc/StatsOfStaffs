<x-default-layout>
    <div class="w-full py-5">
        <x-form.panel>
            <form action="/sessions" method="post" class="flex flex-col">
                @csrf
                <x-form.title>
                    Login
                </x-form.title>

                <x-form.field>
                    <x-form.input name="username" placeholder="Username"
                                  icon="fa-solid fa-at" autocomplete="username" type="text"/>
                </x-form.field>

                <x-form.field>
                    <x-form.input name="password" icon="fa-solid fa-key"
                                  placeholder="Password" autocomplete="current-password" type="password"/>
                </x-form.field>

                <x-form.field class="flex items-center justify-between">
                    <x-form.link link="/password/reset">Forgot Password?</x-form.link>
                    <x-form.checkbox name="rememberme" labeltext="Remember Me"/>
                </x-form.field>

                <x-form.field class="mx-auto">
                    <x-form.primary-button type="submit">Login</x-form.primary-button>
                </x-form.field>

            </form>
        </x-form.panel>
    </div>
</x-default-layout>
