<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dipla')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col items-center justify-center bg-[#FBF9F8] text-[#333333] font-sans antialiased px-4 py-12">

    {{ $slot }}

    <footer class="mt-8 text-center text-sm text-[#333333]/50">
        &copy; {{ date('Y') }} Dipla — Tous droits réservés
    </footer>

    @fluxScripts
</body>
</html>