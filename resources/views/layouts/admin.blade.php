<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Portfolio CMS' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @fluxAppearance
</head>

<body class="min-h-screen bg-zinc-50 dark:bg-zinc-950">

    <flux:sidebar.nav>

        {{-- Dashboard --}}
        <flux:sidebar.item icon="home" href="{{ route('admin.dashboard') }}"
            :current="request()->routeIs('admin.dashboard')">
            Dashboard
        </flux:sidebar.item>


        {{-- Projects --}}
        <flux:sidebar.item icon="folder" href="{{ route('admin.projects.index') }}"
            :current="request()->routeIs('admin.projects.*')">
            Projects
        </flux:sidebar.item>


        {{-- Experiences --}}
        <flux:sidebar.item icon="briefcase" href="{{ route('admin.experiences.index') }}"
            :current="request()->routeIs('admin.experiences.*')">
            Experiences
        </flux:sidebar.item>


        {{-- Skills --}}
        <flux:sidebar.item icon="code-bracket" href="{{ route('admin.skills.index') }}"
            :current="request()->routeIs('admin.skills.*')">
            Skills
        </flux:sidebar.item>


        {{-- Education --}}
        <flux:sidebar.item icon="academic-cap" href="{{ route('admin.education.index') }}"
            :current="request()->routeIs('admin.education.*')">
            Education
        </flux:sidebar.item>


        {{-- Profile --}}
        <flux:sidebar.item icon="user" href="#">
            Profile
        </flux:sidebar.item>

    </flux:sidebar.nav>

    <flux:main>

        {{-- Flash Messages --}}
        @if (session()->has('success') || session()->has('error'))

            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
                class="mb-6">

                @if (session()->has('success'))

                    <flux:callout icon="check-circle" variant="success" class="relative">
                        <div class="flex items-center justify-between gap-4">

                            <flux:text>
                                {{ session('success') }}
                            </flux:text>

                            <flux:button variant="ghost" size="sm" icon="x-mark" square aria-label="Close"
                                @click="show = false" />

                        </div>
                    </flux:callout>

                @elseif (session()->has('error'))

                    <flux:callout icon="x-circle" variant="danger" class="relative">
                        <div class="flex items-center justify-between gap-4">

                            <flux:text>
                                {{ session('error') }}
                            </flux:text>

                            <flux:button variant="ghost" size="sm" icon="x-mark" square aria-label="Close"
                                @click="show = false" />

                        </div>
                    </flux:callout>

                @endif

            </div>

        @endif

        {{ $slot }}

    </flux:main>

    @fluxScripts

</body>

</html>