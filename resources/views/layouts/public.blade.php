<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dipla')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Roboto+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    @auth
        @php
            // On lit puis on efface immédiatement : seule la toute première page
            // publique vue après la connexion doit écraser une position déjà en
            // mémoire (localStorage). Les visites suivantes n'y touchent plus.
            $justLoggedIn = session('just_logged_in', false);
            session()->forget('just_logged_in');
        @endphp
        <script>
            window.diplaUserLocation = {
                label: @js(auth()->user()->address),
                lat: @js(auth()->user()->latitude ? (float) auth()->user()->latitude : null),
                lng: @js(auth()->user()->longitude ? (float) auth()->user()->longitude : null),
            };

            window.diplaJustLoggedIn = @json($justLoggedIn);
        </script>
    @endauth

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-[#FBF9F8] text-[#333333] font-sans antialiased">

    {{-- En-tête fixe : reproduit la nav de la maquette (logo, liens, position, bouton Connexion) --}}
    <header class="fixed top-0 inset-x-0 z-50 border-b border-[#E2E8F0]/60 bg-[#FBF9F8]/90 shadow-sm backdrop-blur-md">
        <nav class="max-w-6xl mx-auto flex items-center justify-between px-4 py-4 md:px-8">
            <a href="{{ route('home') }}" wire:navigate class="text-2xl font-extrabold tracking-tight text-[#1E293B] md:text-3xl">
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
                    <a href="{{ route('search', ['type' => 'commerce']) }}" wire:navigate class="text-[#333333]/70 transition hover:text-[#1E3D59]">
                        Commerces
                    </a>
                </li>
            </ul>

            {{-- Actions desktop : position + connexion --}}
            {{-- Actions : position + connexion (icônes seules sur mobile, texte à partir de md) --}}
            <div class="flex items-center gap-1 md:gap-3">
                @include('partials.location-modal')

                @auth
                    <a href="{{ route('dashboard') }}" wire:navigate aria-label="Mon compte"
                       class="flex items-center gap-2 rounded-full p-2 text-[#1E3D59] transition hover:bg-[#E2E8F0]/60 md:bg-[#1E3D59] md:px-5 md:py-2 md:text-[#FDFBF7] md:hover:bg-[#16293F]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <span class="hidden text-sm font-semibold md:inline">Mon compte</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" wire:navigate aria-label="Connexion"
                       class="flex items-center gap-2 rounded-full p-2 text-[#1E3D59] transition hover:bg-[#E2E8F0]/60 md:bg-[#1E3D59] md:px-5 md:py-2 md:text-[#FDFBF7] md:hover:bg-[#16293F]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <span class="hidden text-sm font-semibold md:inline">Connexion</span>
                    </a>
                @endauth

                {{-- Bouton menu mobile --}}
                <button id="menu-toggle" class="p-2 text-[#1E293B] md:hidden" aria-label="Ouvrir le menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </nav>

        {{-- Menu mobile : en overlay pour ne jamais recouvrir/décaler le contenu de la page --}}
        <ul id="menu-mobile" class="absolute inset-x-0 top-full hidden flex-col gap-1 border-t border-[#E2E8F0]/60 bg-[#FBF9F8] px-4 py-3 text-sm font-semibold text-[#333333]/80 shadow-lg">
            <li>
                <a href="{{ route('home') }}" wire:navigate class="block py-2 {{ request()->routeIs('home') ? 'text-[#1E3D59]' : '' }}">
                    Explorer
                </a>
            </li>
            <li><a href="{{ route('search', ['type' => 'produit']) }}" wire:navigate class="block py-2">Produits</a></li>
            <li><a href="{{ route('search', ['type' => 'commerce']) }}" wire:navigate class="block py-2">Commerces</a></li>
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