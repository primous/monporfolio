@props(['title' => null, 'description' => null])
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ filled((string) $title) ? $title : config('app.name') }}</title>
    @if (filled((string) $description))
        <meta name="description" content="{{ $description }}">
    @endif
    {{-- Favicons (emblème du logo) --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('images/brand/favicon-32.png') }}" type="image/png" sizes="32x32">
    <link rel="icon" href="{{ asset('images/brand/favicon-16.png') }}" type="image/png" sizes="16x16">
    <link rel="apple-touch-icon" href="{{ asset('images/brand/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="theme-color" content="#111111">

    {{-- Les animations .reveal ne masquent le contenu que si JavaScript tourne. --}}
    <script>document.documentElement.classList.add('reveal-ready');</script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
</head>
<body class="flex min-h-screen flex-col bg-white font-body text-ink antialiased selection:bg-primary/10 selection:text-primary">
    <a href="#contenu" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[200] focus:rounded-full focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:shadow-float">
        Aller au contenu
    </a>

    <x-layout.navbar />

    {{-- x-data : racine Alpine (tiroirs, accordéons ; x-intersect des anciennes sections). --}}
    <main id="contenu" x-data class="w-full flex-1">
        {{ $slot }}
    </main>

    <x-layout.footer />

    {{-- Livewire 4 injecte et démarre l'unique instance d'Alpine. --}}
    @livewireScripts
    @stack('scripts')
</body>
</html>
