<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Mon compte — Dipla')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Roboto+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FBF9F8] text-[#333333] font-sans antialiased">

    {{-- Sidebar desktop : même structure/style que celle de l'espace entreprise --}}
    <aside class="hidden md:flex md:flex-col md:w-64 md:fixed md:inset-y-0 md:left-0 bg-[#252623] text-[#E4E2DE] z-30">
        <div class="flex items-center px-6 py-6 border-b border-white/10">
            <a href="{{ route('home') }}" wire:navigate class="text-2xl font-bold tracking-tight text-white hover:text-white/90 transition">
                Dipla
            </a>
        </div>

        <nav class="flex-1 flex flex-col gap-1 px-3 py-6">
            <a href="{{ route('dashboard') }}" wire:navigate
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-[#1E3D59] text-white' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                Mon compte
            </a>
            <a href="{{ route('account.settings') }}" wire:navigate
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('profile.edit', 'security.edit', 'appearance.edit') ? 'bg-[#1E3D59] text-white' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Paramètres
            </a>
        </nav>

        <div class="px-3 pb-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-full bg-[#4A3B5C] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#3a2f49]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- Barre mobile --}}
    <div class="md:hidden fixed top-0 inset-x-0 z-30 flex items-center justify-between border-b border-[#E2E8F0] bg-[#FAFAFF] px-4 py-3">
        <a href="{{ route('home') }}" wire:navigate class="text-lg font-semibold text-[#1E293B]">Dipla</a>
        <button id="user-menu-toggle" class="p-2 text-[#1E293B]" aria-label="Ouvrir le menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
    <nav id="user-menu-mobile" class="hidden md:hidden fixed top-14 inset-x-0 z-30 flex-col gap-1 bg-[#FAFAFF] border-b border-[#E2E8F0] px-4 py-3 text-sm font-medium">
        <a href="{{ route('dashboard') }}" wire:navigate class="block rounded-full px-4 py-2.5 {{ request()->routeIs('dashboard') ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'text-[#1E293B]' }}">Mon compte</a>
                <a href="{{ route('account.settings') }}" wire:navigate class="block rounded-full px-4 py-2.5 {{ request()->routeIs('account.settings') ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'text-[#1E293B]' }}">Paramètres</a>
        <form method="POST" action="{{ route('logout') }}" class="pt-1">
            @csrf
            <button type="submit" class="w-full text-left rounded-full bg-[#4A3B5C] px-4 py-2.5 text-white">Déconnexion</button>
        </form>
    </nav>

    {{-- Contenu --}}
    <div class="md:pl-64">
        <main class="px-4 pt-3 pb-6 md:px-8 md:pt-8 md:pb-10 mt-14 md:mt-0">
            {{ $slot }}
        </main>
    </div>

    <script>
        document.getElementById('user-menu-toggle').addEventListener('click', () => {
            document.getElementById('user-menu-mobile').classList.toggle('hidden');
            document.getElementById('user-menu-mobile').classList.toggle('flex');
        });
    </script>
    @fluxScripts
</body>
</html>