<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script>
        (function () {
            function getInitialColorMode() {
                const persistedColorPreference = window.localStorage.getItem('nightwind-mode');
                const hasPersistedPreference = typeof persistedColorPreference === 'string';
                if (hasPersistedPreference) {
                    return persistedColorPreference;
                }
                const mql = window.matchMedia('(prefers-color-scheme: dark)');
                const hasMediaQueryPreference = typeof mql.matches === 'boolean';
                if (hasMediaQueryPreference) {
                    return mql.matches ? 'dark' : 'light';
                }
                return 'light';
            }

            getInitialColorMode() == 'light' ? document.documentElement.classList.remove('dark') : document.documentElement.classList.add('dark');
        })();
    </script>
    {{--  Icon  --}}
    <link rel="icon" href="{{ asset('imgs/icon.ico') }}" type="image/x-icon"/>

    @vite(['resources/css/app.css'])
    @vite(['resources/css/styles.css'])
    <title>Stats Of Staffs</title>
</head>
<body
    class="relative flex h-screen w-screen flex-col overflow-x-auto bg-white text-gray-700 min-w-[280px] max-w-screen pt-[64px] dark:bg-slate-300">
{{ $slot }}

@livewire('flash-message')

@vite(['resources/js/app.js'])
</body>
</html>
