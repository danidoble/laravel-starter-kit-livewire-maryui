<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <x-menu class="!w-50" activate-by-route>
            <x-menu-item :title="__('Profile')" :link="route('settings.profile')" wire:navigate/>
            <x-menu-item :title="__('Password')" :link="route('settings.password')" wire:navigate/>
            <x-menu-item :title="__('Appearance')" :link="route('settings.appearance')" wire:navigate/>
        </x-menu>
    </div>

    <div class="divider"></div>

    <div class="flex-1 self-stretch max-md:pt-6">
        @if($heading)
        <x-slot:title>{{ $heading }}</x-slot:title>
        <h2 class="text-lg font-medium">{{ $heading }}</h2>
        @endif
        @if($subheading)
        <p class="text-sm text-base-content/60">{{ $subheading }}</p>
        @endif

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
