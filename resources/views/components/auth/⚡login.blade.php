<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $credentials = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $this->remember)) {
            $this->addError('email', 'The provided credentials are incorrect.');

            return;
        }

        request()->session()->regenerate();

        $this->redirectRoute('admin.dashboard');
    }
};
?>

<div class="flex min-h-screen items-center justify-center bg-zinc-50 px-6 dark:bg-zinc-950">

    <div class="w-full max-w-md">

        <div class="mb-8 text-center">
            <flux:heading size="xl">
                Portfolio CMS
            </flux:heading>

            <flux:text class="mt-2">
                Sign in to your admin account
            </flux:text>
        </div>

        <form wire:submit="login" class="space-y-6">

            <flux:input
                wire:model="email"
                label="Email"
                type="email"
                placeholder="admin@example.com"
                autocomplete="email"
                :invalid="$errors->has('email')"
            />

            @error('email')
                <flux:text variant="danger">
                    {{ $message }}
                </flux:text>
            @enderror

            <flux:input
                wire:model="password"
                label="Password"
                type="password"
                placeholder="••••••••"
                autocomplete="current-password"
                :invalid="$errors->has('password')"
            />

            @error('password')
                <flux:text variant="danger">
                    {{ $message }}
                </flux:text>
            @enderror

            <flux:checkbox
                wire:model="remember"
                label="Remember me"
            />

            <flux:button
                type="submit"
                variant="primary"
                class="w-full"
            >
                Sign in
            </flux:button>

        </form>

    </div>

</div>