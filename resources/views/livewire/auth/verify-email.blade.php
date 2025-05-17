<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6 w-full h-screen items-center justify-center p-2 sm:p-4">
    <section class="card w-md bg-base-200 shadow-sm">
        <div class="card-body">
                <h1 class="text-xl font-semibold text-center">{{ __('Verify email address') }}</h1>
                <p class="text-sm text-center text-base-content">
                    {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
                </p>
                @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success shadow-sm rounded-md">
                            <div class="flex-1">
                                <label>{{ __('A new verification link has been sent to the email address you provided during registration.') }}</label>
                            </div>
                        </div>
                    @endsession

                <div class="mt-6 grid gap-2">
                    <x-button class="btn btn-primary btn-block" wire:click="sendVerification" spinner="sendVerification">{{ 'Resend verification email' }}</x-button>
                    <x-button class="btn btn-secondary btn-block" wire:click="logout" spinner="logout">{{ 'Log out' }}</x-button>
                </div>
    </section>
</div>
