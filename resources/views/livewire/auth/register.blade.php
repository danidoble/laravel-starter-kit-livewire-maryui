<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'alpha_dash', 'max:255', 'unique:' . User::class],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6 w-full h-screen items-center justify-center p-2 sm:p-4">
    <section class="card w-md bg-base-200 shadow-sm">
        <div class="card-body">
            <x-form wire:submit="register">
                <h1 class="text-xl font-semibold text-center">{{ __('Create an account') }}</h1>
                <p class="text-sm text-center text-base-content/60">
                    {{ __('Enter your details below to create your account') }}
                </p>

                <x-input label="{{ __('Name') }}" wire:model="name" placeholder="{{ __('Full name') }}" autofocus />
                <x-input label="{{ __('Username') }}" wire:model="username" placeholder="username"
                    autocomplete="username" />
                <x-input label="{{ __('Email address') }}" wire:model="email" placeholder="test@example.com"
                    autocomplete="email" />
                <x-password label="{{ __('Password') }}" wire:model="password" placeholder="*********" right
                    autocomplete="new-password" />
                <x-password label="{{ __('Confirm password') }}" wire:model="password_confirmation"
                    placeholder="*********" right autocomplete="new-password" />
                <div class="mt-6 grid gap-2">
                    <x-button type="submit" class="btn btn-primary btn-block"
                        spinner="register">{{ 'Create account' }}</x-button>
                </div>
                <div class="mt-2 inline-flex gap-x-1 justify-center items-center">
                    <span>{{ __('Already have an account?') }}</span>
                    <a href="{{ route('login') }}" class="underline">{{ __('Log in') }}</a>
                </div>
            </x-form>
    </section>
</div>
