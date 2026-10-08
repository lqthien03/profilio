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
        <flux:sidebar.item icon="code-bracket" href="#">
            Skills
        </flux:sidebar.item>


        {{-- Education --}}
        <flux:sidebar.item icon="academic-cap" href="#">
            Education
        </flux:sidebar.item>


        {{-- Profile --}}
        <flux:sidebar.item icon="user" href="#">
            Profile
        </flux:sidebar.item>

    </flux:sidebar.nav>

    <flux:main>

        {{-- Flash Messages --}}
        @if (session()->has('success'))
            <flux:callout icon="check-circle" variant="success" class="mb-6">
                {{ session('success') }}
            </flux:callout>
        @endif

        @if (session()->has('error'))
            <flux:callout icon="x-circle" variant="danger" class="mb-6">
                {{ session('error') }}
            </flux:callout>
        @endif

        {{ $slot }}

    </flux:main>

    @fluxScripts

</body>

</html>