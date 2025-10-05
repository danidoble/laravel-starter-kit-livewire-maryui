<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('A reset link will be sent if the account exists.'));
    }
}; ?>

<div class="flex flex-col gap-6 w-full h-screen items-center justify-center p-2 sm:p-4">
    <section class="card w-md bg-base-200 shadow-sm">
        <div class="card-body">
            <x-form method="POST" wire:submit="sendPasswordResetLink">
                <h1 class="text-xl font-semibold text-center">{{ __('Forgot password') }}</h1>
                <p class="text-sm text-center text-base-content/60">
                    {{ __('Enter your email to receive a password reset link') }}
                </p>

                <x-input label="{{ __('Email address') }}" wire:model="email" placeholder="test@example.com" autofocus
                    autocomplete="email" />

                @session('status')
                    <div class="alert alert-success shadow-sm rounded-md">
                        <div class="flex-1">
                            <label>{{ session('status') }}</label>
                        </div>
                    </div>
                @endsession

                <div class="mt-6 grid gap-2">
                    <x-button type="submit" class="btn btn-primary btn-block"
                        spinner="sendPasswordResetLink">{{ 'Email password reset link' }}</x-button>
                </div>
                <div class="mt-2 grid gap-2">
                    <div class="inline-flex gap-x-1 justify-center items-center">
                        <span>Or, return to</span>
                        <a href="{{ route('login') }}" class="underline">{{ __('Log in') }}</a>
                    </div>
                </div>
            </x-form>
    </section>
</div>
