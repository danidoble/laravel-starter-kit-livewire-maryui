<x-layouts.auth>
    <div class="flex flex-col gap-6 w-full h-screen items-center justify-center p-2 sm:p-4">
    <section class="card w-md bg-base-200 shadow-sm">
        <div class="card-body">
    <div class="flex flex-col gap-6">
        <div
            class="relative w-full h-auto"
            x-cloak
            x-data="{
                showRecoveryInput: @js($errors->has('recovery_code')),
                code: '',
                recovery_code: '',
                toggleInput() {
                    this.showRecoveryInput = !this.showRecoveryInput;

                    this.code = '';
                    this.recovery_code = '';

                    $dispatch('clear-2fa-auth-code');

                    $nextTick(() => {
                        this.showRecoveryInput
                            ? this.$refs.recovery_code?.focus()
                            : $dispatch('focus-2fa-auth-code');
                    });
                },
            }"
        >
            <div x-show="!showRecoveryInput">
                <h1 class="text-xl font-semibold text-center">{{ __('Authentication Code') }}</h1>
                <p class="text-sm text-center text-base-content/60">
                    {{ __('Enter the authentication code provided by your authenticator application.') }}
                </p>
            </div>

            <div x-show="showRecoveryInput">
                <h1 class="text-xl font-semibold text-center">{{ __('Recovery Code') }}</h1>
                <p class="text-sm text-center text-base-content/60">
                    {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
                </p>
            </div>

            <form method="POST" action="{{ route('two-factor.login.store') }}">
                @csrf

                <div class="space-y-5 text-center">
                    <div x-show="!showRecoveryInput">
                        <div class="flex items-center justify-center my-5">
                            <x-input-otp
                                name="code"
                                digits="6"
                                autocomplete="one-time-code"
                                x-model="code"
                            />
                        </div>

                        @error('code')
                            <p class="[:where(&)]:text-sm text-red-600 dark:text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div x-show="showRecoveryInput">
                        <div class="my-5">
                            <x-input
                                type="text"
                                name="recovery_code"
                                x-ref="recovery_code"
                                x-bind:required="showRecoveryInput"
                                autocomplete="one-time-code"
                                x-model="recovery_code"
                            />
                        </div>

                        @error('recovery_code')
                            <p class="[:where(&)]:text-sm text-red-600 dark:text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <x-button
                        type="submit"
                        class="btn btn-primary btn-block"
                    >
                        {{ __('Continue') }}
                    </x-button>
                </div>

                <div class="mt-5 space-x-0.5 text-sm leading-5 text-center">
                    <span class="opacity-50">{{ __('or you can') }}</span>
                    <div class="inline font-medium underline cursor-pointer opacity-80">
                        <span x-show="!showRecoveryInput" @click="toggleInput()">{{ __('login using a recovery code') }}</span>
                        <span x-show="showRecoveryInput" @click="toggleInput()">{{ __('login using an authentication code') }}</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
    </section></div>
</x-layouts.auth>
