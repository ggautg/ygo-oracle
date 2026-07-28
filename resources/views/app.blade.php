<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=IBM+Plex+Mono:wght@400;500&display=swap"
        rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @php
        $ogTitle = 'El Corazón de las Cartas';
        $ogDescription = 'Un oráculo hecho con cartas reales de Yu-Gi-Oh — sin IA, sin invento, solo datos del juego.';
        $ogImage = asset('images/og-default.jpg');

        if (($page['component'] ?? null) === 'Tarot/Shared') {
            $reading = $page['props']['reading'] ?? null;
            if ($reading) {
                $ogTitle = 'Una tirada del Corazón de las Cartas';
                $ogDescription = $reading['mystic_message'] ?? $ogDescription;
                $ogImage = $reading['cards'][0]['image_url'] ?? $ogImage;
            }
        }
    @endphp

    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>