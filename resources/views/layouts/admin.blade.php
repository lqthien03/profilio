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

    <flux:sidebar>
        <flux:sidebar.header>
            <flux:sidebar.brand href="{{ route('admin.dashboard') }}" name="Portfolio CMS" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" href="{{ route('admin.dashboard') }}"
                :current="request()->routeIs('admin.dashboard')">
                Dashboard
            </flux:sidebar.item>

            <flux:sidebar.item icon="folder" href="{{ route('admin.projects.index') }}"
                :current="request()->routeIs('admin.projects.*')">
                Projects
            </flux:sidebar.item>

            <flux:sidebar.item icon="briefcase" href="#">
                Experience
            </flux:sidebar.item>

            <flux:sidebar.item icon="code-bracket" href="#">
                Skills
            </flux:sidebar.item>

            <flux:sidebar.item icon="academic-cap" href="#">
                Certificates
            </flux:sidebar.item>

            <flux:sidebar.item icon="user" href="#">
                Profile
            </flux:sidebar.item>
        </flux:sidebar.nav>

        <flux:spacer />

        <flux:dropdown position="top" align="start">
            <flux:profile :name="auth()->user()->name" :initials="strtoupper(substr(auth()->user()->name, 0, 1))"
                icon-trailing="chevron-up-down" />

            <flux:menu>
                <flux:menu.item icon="user">
                    Profile
                </flux:menu.item>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle">
                        Logout
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <flux:main>
        {{ $slot }}
    </flux:main>

    @fluxScripts

</body>

</html>