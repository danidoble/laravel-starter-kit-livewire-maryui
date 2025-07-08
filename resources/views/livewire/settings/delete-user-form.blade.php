<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';
    public bool $modal = false;

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <h3 class="text-semibold">{{ __('Delete account') }}</h3>
        <p class="text-sm text-base-content/80">{{ __('Delete your account and all of its resources') }}</p>

    </div>

    <x-button :label="__('Delete account')" wire:click="modal=true" class="btn-error" />

    <x-modal wire:model="modal" class="backdrop-blur">
        <h3 class="text-lg font-semibold">{{ __('Are you sure you want to delete your account?') }}</h3>

        <p class="my-3 text-sm text-base-content/80">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
        </p>

        <x-input wire:model="password" :label="__('Password')" type="password" required />

        <x-slot:actions>
            <x-button class="btn-secondary" :label="__('Cancel')" wire:click="modal=false" />
            <x-button class="btn-error" :label="__('Delete account')" wire:click="deleteUser" />
        </x-slot:actions>
    </x-modal>

</section>
