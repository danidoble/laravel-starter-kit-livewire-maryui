<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<section class="w-full">
    @include('livewire.settings.heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Commodi accusamus deserunt laboriosam quas ipsum voluptate reiciendis sequi impedit officiis neque!
    </x-settings.layout>
</section>
