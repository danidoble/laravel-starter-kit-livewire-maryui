<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (
            !Auth::guard('web')->validate([
                'email' => Auth::user()->email,
                'password' => $this->password,
            ])
        ) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6 w-full h-screen items-center justify-center p-2 sm:p-4">
    <section class="card w-md bg-base-200 shadow-sm">
        <div class="card-body">
            <x-form wire:submit="confirmPassword">
                <h1 class="text-xl font-semibold text-center">{{ __('Confirm password') }}</h1>
                <p class="text-sm text-center text-base-content/60">
                    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                </p>
                @session('status')
                    <div class="alert alert-success shadow-sm rounded-md">
                        <div class="flex-1">
                            <label>{{ session('status') }}</label>
                        </div>
                    </div>
                @endsession


                <x-input label="{{ __('Password') }}" wire:model="password" placeholder="********" right autofocus
                    autocomplete="new-password" />


                <div class="mt-6 grid gap-2">
                    <x-button type="submit" class="btn btn-primary btn-block"
                        spinner="confirmPassword">{{ 'Confirm' }}</x-button>
                </div>
            </x-form>
    </section>
</div>
