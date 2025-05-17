<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string')]
    public string $username = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        $credentials = [
            'username' => $this->username,
            'password' => $this->password,
        ];
        if (filter_var($this->username, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $this->username;
            unset($credentials['username']);
        }

        if (!Auth::attempt($credentials, $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->username) . '|' . request()->ip());
    }
}; ?>

<div class="flex flex-col gap-6 w-full h-screen items-center justify-center p-2 sm:p-4">
    <section class="card w-md bg-base-200 shadow-sm">
        <div class="card-body">
            <x-form wire:submit="login">
                <h1 class="text-xl font-semibold text-center">{{ __('Log in to your account') }}</h1>
                <p class="text-sm text-center text-base-content/60">
                    {{ __('Enter your username or your email and password below to log in') }}
                </p>

                @session('status')
                    <div class="alert alert-success shadow-sm rounded-md">
                        <div class="flex-1">
                            <label>{{ session('status') }}</label>
                        </div>
                    </div>
                @endsession

                <x-input label="{{ __('Username or email address') }}" wire:model="username" placeholder="username"
                    autofocus autocomplete="username" />
                <x-password label="{{ __('Password') }}" wire:model="password" placeholder="*********" right
                    autocomplete="current-password" />
                <x-checkbox label="{{ __('Remember me') }}" wire:model="remember" />
                <div class="mt-6 grid gap-2">
                    <x-button type="submit" class="btn btn-primary btn-block" spinner="login">{{ 'Log in' }}</x-button>
                </div>
                <div class="mt-2 grid gap-2">
                    <a href="{{ route('password.request') }}"
                        class="btn btn-link btn-block">{{ __('Forgot password?') }}</a>
                    @if (Route::has('register'))
                        <div class="inline-flex gap-x-1 justify-center items-center">
                            <span>Don't have an account?</span>
                            <a href="{{ route('register') }}" class="underline">{{ __('Sign up') }}</a>
                        </div>
                    @endif
                </div>
            </x-form>
    </section>
</div>
