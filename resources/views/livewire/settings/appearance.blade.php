<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<section class="w-full">
    @include('livewire.settings.heading')

    <x-settings.layout :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">

        <div class="join w-full appearance-container">
            <x-button variant="primary" class="flex-1 join-item" @click="$dispatch('mary-toggle-theme', 'light')">
                <x-icon name="o-sun" />
                {{ __('Light') }}
            </x-button>
            <x-button variant="primary" class="flex-1 join-item" @click="$dispatch('mary-toggle-theme', 'dark')">
                <x-icon name="o-moon" />
                {{ __('Dark') }}
            </x-button>
            <x-button variant="primary" class="flex-1 join-item" @click="$dispatch('mary-toggle-theme', 'system')">
                <x-icon name="o-computer-desktop" />
                {{ __('System') }}
            </x-button>
        </div>

    </x-settings.layout>
</section>
