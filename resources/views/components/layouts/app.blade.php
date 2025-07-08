<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased bg-base-100">

    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand>
            <label for="main-drawer" class="lg:hidden me-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:brand>
        <x-slot:actions>
            <x-profile-dropdown :sidebar="false" />
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main-custom full-width>
        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer"  class="bg-base-300 lg:bg-inherit">

            {{-- BRAND --}}
            <x-app-brand class="px-5 pt-4" />

            {{-- MENU --}}
            <x-menu activate-by-route :title="null">

                <x-menu-item :title="__('Dashboard')" icon="o-home" :link="route('dashboard')" />


                <x-menu-sub title="More" icon="o-cog-6-tooth">
                    <x-menu-item title="Wifi" icon="o-wifi" link="####" />
                    <x-menu-item title="Archives" icon="o-archive-box" link="####" />
                </x-menu-sub>

            </x-menu>
        </x-slot:sidebar>

        <x-slot:sidebar-footer>
            {{-- SIDEBAR FOOTER --}}
            <x-menu activate-by-route :title="null">
                <x-menu-item :title="__('Repository')" icon="o-folder"
                    link="https://github.com/danidoble/laravel-starter-kit-livewire-maryui" external no-wire-navigate />
                <x-menu-item :title="__('Documentation')" icon="o-book-open" link="https://laravel.com/docs/starter-kits#livewire"
                    external no-wire-navigate />

                <x-profile-dropdown />

                {{-- <div class="p-4 text-xs text-center text-base-content/50">
                    <span class="text-xs">Powered by</span>
                    <a href="https://github.com/danidoble" target="_blank" class="link link-hover text-xs">danidoble</a>
                </div> --}}
            </x-menu>

        </x-slot:sidebar-footer>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main-custom>


    <div class="hidden"><x-theme-toggle-custom /></div>
    {{--  TOAST area --}}
    <x-toast />
</body>

</html>
