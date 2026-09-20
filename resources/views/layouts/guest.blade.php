<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dipla')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col items-center justify-center bg-[#FBF9F8] text-[#333333] font-sans antialiased px-4 py-12">
    @stack('body-top')
    {{ $slot }}

    <footer class="relative z-10 mt-8 text-center text-sm text-[#333333]/70">
        <nav aria-label="Informations légales" class="mb-2 flex flex-col items-center gap-1 sm:flex-row sm:justify-center sm:gap-6">
            <a href="{{ route('legal.mentions') }}" wire:navigate class="py-1 hover:text-[#1E3D59] hover:underline">Mentions légales</a>
            <a href="{{ route('legal.privacy') }}" wire:navigate class="py-1 hover:text-[#1E3D59] hover:underline">Politique de confidentialité</a>
            <a href="{{ route('legal.terms') }}" wire:navigate class="py-1 hover:text-[#1E3D59] hover:underline">Conditions d'utilisation</a>
            <a href="{{ route('contact') }}" wire:navigate class="py-1 hover:text-[#1E3D59] hover:underline">Contact</a>
        </nav>
        <p>&copy; {{ date('Y') }} Dipla — Tous droits réservés</p>
    </footer>
    @include('partials.cookie-notice')
    @fluxScripts
</body>
</html>