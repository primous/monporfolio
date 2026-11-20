@props([
    'title' => null,
    'description' => null,
    'canonical' => null,      // URL canonique imposée ; sinon URL courante (+ ?categorie / ?page utiles)
    'image' => null,          // image de partage (1200 × 630) ; sinon image par défaut du site
    'ogType' => 'website',    // 'article' pour les articles du blog
    'noindex' => false,
])
@php
    $titre = filled((string) $title) ? (string) $title : config('app.name');
    $desc = filled((string) $description) ? (string) $description : null;

    // URL canonique : sans paramètres parasites (utm, fbclid…) ; seuls ?categorie et ?page>1 sont gardés.
    $params = array_filter([
        'categorie' => request()->query('categorie'),
        'page' => (int) request()->query('page', 1) > 1 ? (int) request()->query('page') : null,
    ]);
    $canon = $canonical ?: (url()->current().($params ? '?'.http_build_query($params) : ''));
    $ogImage = $image ?: asset('images/brand/og-default.jpg');
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $titre }}</title>
    @if ($desc)
        <meta name="description" content="{{ $desc }}">
    @endif
    <link rel="canonical" href="{{ $canon }}">
    <meta name="robots" content="{{ $noindex ? 'noindex, follow' : 'index, follow, max-image-preview:large' }}">

    {{-- Partage (Facebook, WhatsApp, LinkedIn, X) --}}
    <meta property="og:site_name" content="Hounkpatin Primous">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:title" content="{{ $titre }}">
    @if ($desc)
        <meta property="og:description" content="{{ $desc }}">
    @endif
    <meta property="og:url" content="{{ $canon }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Hounkpatin Primous, création de sites internet à Cotonou">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $titre }}">
    @if ($desc)
        <meta name="twitter:description" content="{{ $desc }}">
    @endif
    <meta name="twitter:image" content="{{ $ogImage }}">

    {{-- Données structurées : entreprise + site (toutes les pages), puis celles de la page --}}
    @include('seo.schema-site')
    @stack('schema')
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
