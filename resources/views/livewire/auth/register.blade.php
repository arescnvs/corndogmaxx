<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Buat Akun')" :description="__('Masukkan detail untuk membuat akun')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="register" class="flex flex-col gap-6">
        <!-- Nama Kasir -->
        <flux:input
            wire:model="namaKasir"
            :label="__('Nama Kasir')"
            type="text"
            required
            autofocus
            autocomplete="name"
            placeholder="Masukkan nama lengkap"
        />

        <!-- Username -->
        <flux:input
            wire:model="username"
            :label="__('Username')"
            type="text"
            required
            autocomplete="username"
            placeholder="Masukkan username"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Password')"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirm password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirm password')"
            viewable
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>{{ __('Already have an account?') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>
