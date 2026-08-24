<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dipla')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-[#FBF9F8] text-[#333333] font-sans antialiased">

    {{-- En-tête fixe : reproduit la nav de la maquette (logo, liens, position, bouton Connexion) --}}
    <header class="fixed top-0 inset-x-0 z-50 border-b border-[#E2E8F0]/60 bg-[#FBF9F8]/90 shadow-sm backdrop-blur-md">
        <nav class="max-w-6xl mx-auto flex items-center justify-between px-4 py-4 md:px-8">
            <a href="{{ route('home') }}" wire:navigate class="text-xl font-extrabold tracking-tight text-[#1E293B]">
                Dipla
            </a>

            {{-- Liens desktop --}}
            <ul class="hidden md:flex items-center gap-6 text-sm font-semibold">
                <li>
                    <a href="{{ route('home') }}" wire:navigate
                       class="pb-1 transition {{ request()->routeIs('home') ? 'border-b-2 border-[#1E3D59] text-[#1E3D59]' : 'text-[#333333]/70 hover:text-[#1E3D59]' }}">
                        Explorer
                    </a>
                </li>
                <li>
                    <a href="{{ route('search', ['type' => 'produit']) }}" wire:navigate class="text-[#333333]/70 transition hover:text-[#1E3D59]">
                        Produits
                    </a>
                </li>
                <li>
                    <a href="{{ route('search', ['type' => 'service']) }}" wire:navigate class="text-[#333333]/70 transition hover:text-[#1E3D59]">
                        Commerces
                    </a>
                </li>
            </ul>

            {{-- Actions desktop : position + connexion --}}
            <div class="hidden md:flex items-center gap-3">
                @include('partials.location-modal')

                @auth
                    <a href="{{ route('dashboard') }}" wire:navigate
                       class="rounded-full bg-[#1E3D59] px-5 py-2 text-sm font-semibold text-[#FDFBF7] transition hover:bg-[#16293F]">
                        Mon compte
                    </a>
                @else
                    <a href="{{ route('login') }}" wire:navigate
                       class="rounded-full bg-[#1E3D59] px-5 py-2 text-sm font-semibold text-[#FDFBF7] transition hover:bg-[#16293F]">
                        Connexion
                    </a>
                @endauth
            </div>

            {{-- Bouton menu mobile --}}
            <button id="menu-toggle" class="p-2 text-[#1E293B] md:hidden" aria-label="Ouvrir le menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </nav>

        {{-- Menu mobile : en overlay pour ne jamais recouvrir/décaler le contenu de la page --}}
        <ul id="menu-mobile" class="absolute inset-x-0 top-full hidden flex-col gap-1 border-t border-[#E2E8F0]/60 bg-[#FBF9F8] px-4 py-3 text-sm font-semibold text-[#333333]/80 shadow-lg">
            <li>
                <a href="{{ route('home') }}" wire:navigate class="block py-2 {{ request()->routeIs('home') ? 'text-[#1E3D59]' : '' }}">
                    Explorer
                </a>
            </li>
            <li><a href="{{ route('search', ['type' => 'produit']) }}" wire:navigate class="block py-2">Produits</a></li>
            <li><a href="{{ route('search', ['type' => 'service']) }}" wire:navigate class="block py-2">Commerces</a></li>
            <li><a href="{{ route('search', ['mode' => 'nearby']) }}" wire:navigate class="block py-2">À proximité</a></li>
            @auth
                <li><a href="{{ route('dashboard') }}" wire:navigate class="block py-2 font-bold text-[#1E3D59]">Mon compte</a></li>
            @else
                <li><a href="{{ route('login') }}" wire:navigate class="block py-2 font-bold text-[#1E3D59]">Connexion</a></li>
            @endauth
        </ul>
    </header>

    <main class="mx-auto w-full max-w-6xl flex-1 px-4 pb-10 pt-28">
        {{ $slot }}
    </main>

    <footer class="border-t border-[#E2E8F0] py-8 text-center text-sm text-[#333333]/50">
        &copy; {{ date('Y') }} Dipla — Tous droits réservés
    </footer>

    <script>
        document.getElementById('menu-toggle').addEventListener('click', () => {
            document.getElementById('menu-mobile').classList.toggle('hidden');
            document.getElementById('menu-mobile').classList.toggle('flex');
        });
    </script>
    @fluxScripts
</body>
</html>