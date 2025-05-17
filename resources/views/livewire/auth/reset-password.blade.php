<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PasswordReset) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6 w-full h-screen items-center justify-center p-2 sm:p-4">
    <section class="card w-md bg-base-200 shadow-sm">
        <div class="card-body">
            <x-form wire:submit="resetPassword">
                <h1 class="text-xl font-semibold text-center">{{ __('Reset password') }}</h1>
                <p class="text-sm text-center text-base-content/60">
                    {{ __('Please enter your new password below') }}
                </p>

                <x-input label="{{ __('Email address') }}" wire:model="email" placeholder="email" autocomplete="email" />
                <x-password label="{{ __('Password') }}" wire:model="password" placeholder="*********" right autocomplete="new-password" />
                <x-password label="{{ __('Confirm password') }}" wire:model="password_confirmation" placeholder="*********" right autocomplete="new-password" />

                <div class="mt-6 grid gap-2">
                    <x-button type="submit" class="btn btn-primary btn-block" spinner="resetPassword">{{ 'Reset password' }}</x-button>
                </div>
            </x-form>
    </section>
</div>
