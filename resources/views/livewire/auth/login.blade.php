<div class="flex flex-col gap-6">
    <img src="/images/cmremove.png" alt="Logo" class="mx-auto" style="height: 160px; width: auto;">
    <x-auth-header :title="__('Dashboard Kasir')" :description="__('Masukkan username dan password untuk login')" />

    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="login" class="flex flex-col gap-6">
        <flux:input
            wire:model="username"
            :label="__('Username')"
            type="text"
            required
            autofocus
            autocomplete="username"
            placeholder="Masukkan username Anda"
        />

        <div class="relative">
            <flux:input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />
        </div>

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                {{ __('Log in') }}
            </flux:button>
        </div>
    </form>
</div>
