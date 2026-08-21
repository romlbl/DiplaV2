<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dipla')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-canvas text-ink-950 font-sans antialiased">

    <header class="border-b border-line">
        <nav class="max-w-6xl mx-auto px-4 py-5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-display text-xl font-semibold tracking-tight text-ink-950">
                Dipla
            </a>

            <button id="menu-toggle" class="md:hidden p-2 text-ink-950" aria-label="Ouvrir le menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <ul class="hidden md:flex gap-8 text-sm font-medium items-center text-ink-700">
                <li><a href="#" class="hover:text-ink-950 border-b border-transparent hover:border-accent-700 transition pb-0.5">Rechercher</a></li>
                @auth
                    <li><a href="{{ route('dashboard') }}" wire:navigate class="hover:text-ink-950 border-b border-transparent hover:border-accent-700 transition pb-0.5">Mon compte</a></li>
                @else
                    <li><a href="{{ route('login') }}" wire:navigate class="hover:text-ink-950 border-b border-transparent hover:border-accent-700 transition pb-0.5">Connexion</a></li>
                    <li>
                        <a href="#" class="bg-accent-700 text-white px-4 py-2 rounded-md text-sm hover:bg-accent-600 transition">
                            Espace entreprise
                        </a>
                    </li>
                @endauth
            </ul>
        </nav>

        <ul id="menu-mobile" class="hidden md:hidden flex-col gap-1 px-4 pb-4 text-sm font-medium text-ink-700 border-t border-line pt-3">
            <li><a href="#" class="block py-2">Rechercher</a></li>
            @auth
                <li><a href="{{ route('dashboard') }}" wire:navigate class="block py-2">Mon compte</a></li>
            @else
                <li><a href="{{ route('login') }}" wire:navigate class="block py-2">Connexion</a></li>
                <li><a href="#" class="block py-2">Espace entreprise</a></li>
            @endauth
        </ul>
    </header>

    <main class="flex-1 max-w-6xl mx-auto w-full px-4 py-10">
        {{ $slot }}
    </main>

    <footer class="border-t border-line py-8 text-center text-sm text-ink-400">
        &copy; {{ date('Y') }} Dipla — Tous droits réservés
    </footer>

    <script>
        document.getElementById('menu-toggle').addEventListener('click', () => {
            document.getElementById('menu-mobile').classList.toggle('hidden');
        });
    </script>
    @fluxScripts
</body>
</html>