@props(['sidebar'=>true])

<x-dropdown-custom :top="true" class-container="w-full" class-pop="min-w-50">
    {{-- Trigger --}}
    <x-slot:trigger>
        <x-button class="btn-ghost hover:bg-secondary w-full h-fit py-1 flex-nowrap">
            <div class="relative flex items-center justify-center">
                <div class="bg-base-300 rounded-lg size-8 text-center grid place-items-center">
                    {{ auth()->user()->initials() }}
                </div>
            </div>

            @if($sidebar)
            <div class="text-sm font-semibold hidden-when-collapsed text-left flex-1">{{ auth()->user()->name }}</div>
            <x-icon name="o-chevron-up-down" class="ms-1 hidden-when-collapsed" />
            @else
            <div class="flex-1 flex justify-end items-center">
            <x-icon name="o-chevron-down" class="ms-1 hidden-when-collapsed" />
            </div>
            @endif
        </x-button>
    </x-slot:trigger>
    <div class="px-2 py-2 border-b border-base-content/10">
        <div class="relative flex items-center justify-center gap-2">
            <div class="bg-base-300 rounded-lg size-8 text-center grid place-items-center">
                {{ auth()->user()->initials() }}
            </div>
            <div class="flex-1">
                <p class="text-sm overflow-hidden break-all line-clamp-1 font-semibold text-left flex-1">
                    {{ auth()->user()->name }}</p>
                <p class="text-xs overflow-hidden break-all line-clamp-1 font-medium text-left flex-1">
                    &#64;{{ auth()->user()->username }}</p>
            </div>
        </div>
    </div>
    <x-menu-item :title="__('Settings')" icon="o-cog" :link="route('settings.profile')" />
    <form method="POST" action="{{ route('logout') }}" class="w-full">
        @csrf
        <x-button type="submit" :label="__('Log Out')" icon="o-arrow-left-start-on-rectangle"
            class="btn-ghost w-full justify-start" />
    </form>
</x-dropdown-custom>
