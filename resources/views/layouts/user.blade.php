<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Mon compte — Dipla')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex bg-[#FDFBF7] text-[#333333] font-sans antialiased">

    {{-- Sidebar desktop --}}
    <aside class="hidden md:flex md:flex-col md:w-64 md:shrink-0 border-r border-[#E2E8F0] bg-[#FAFAFF] px-4 py-6">
        <a href="{{ route('home') }}" class="text-xl font-semibold tracking-tight text-[#1E293B] px-2 mb-8">
            Dipla
        </a>

        <nav class="flex flex-col gap-1 text-sm font-medium">
            <a href="{{ route('dashboard') }}"
               class="rounded-full px-4 py-2.5 transition {{ request()->routeIs('dashboard') ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'text-[#1E293B] hover:bg-[#FDFBF7]' }}">
                Mon compte
            </a>
            <a href="{{ route('search') }}" wire:navigate
               class="rounded-full px-4 py-2.5 transition text-[#1E293B] hover:bg-[#FDFBF7]">
                Rechercher
            </a>
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
            @csrf
            <button type="submit" class="w-full text-left rounded-full px-4 py-2.5 text-sm font-medium text-[#4A3B5C] transition hover:bg-[#FDFBF7]">
                Se déconnecter
            </button>
        </form>
    </aside>

    {{-- Barre mobile --}}
    <div class="md:hidden fixed top-0 inset-x-0 z-20 flex items-center justify-between border-b border-[#E2E8F0] bg-[#FAFAFF] px-4 py-3">
        <a href="{{ route('home') }}" class="text-lg font-semibold text-[#1E293B]">Dipla</a>
        <button id="user-menu-toggle" class="p-2 text-[#1E293B]" aria-label="Ouvrir le menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
    <nav id="user-menu-mobile" class="hidden md:hidden fixed top-14 inset-x-0 z-20 flex-col gap-1 bg-[#FAFAFF] border-b border-[#E2E8F0] px-4 py-3 text-sm font-medium">
        <a href="{{ route('dashboard') }}" class="block rounded-full px-4 py-2.5 {{ request()->routeIs('account.dashboard') ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'text-[#1E293B]' }}">Mon compte</a>
        <a href="{{ route('search') }}" wire:navigate class="block rounded-full px-4 py-2.5 text-[#1E293B]">Rechercher</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left rounded-full px-4 py-2.5 text-[#4A3B5C]">Se déconnecter</button>
        </form>
    </nav>

    <main class="flex-1 px-4 py-6 md:py-10 mt-14 md:mt-0">
        <div class="max-w-3xl mx-auto">
            {{ $slot }}
        </div>
    </main>

    <script>
        document.getElementById('user-menu-toggle').addEventListener('click', () => {
            document.getElementById('user-menu-mobile').classList.toggle('hidden');
        });
    </script>
    @fluxScripts
</body>
</html>