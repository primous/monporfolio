{{--
  En-tête des pages intérieures : même panneau noir que l'accueil, plus compact.
  Props : badge (eyebrow), title (H1), intro. Slot optionnel : actions (boutons).
--}}
@props(['badge' => null, 'title', 'intro' => null, 'crumb' => null, 'breadcrumbs' => null])
@php
  // Fil d'Ariane structuré (BreadcrumbList). Par défaut : Accueil > page courante.
  // Une page peut fournir sa propre liste via :breadcrumbs="[[nom, url], …]".
  $items = $breadcrumbs ?? [['Accueil', route('home')], [$badge ?? $title, url()->current()]];
  $breadcrumbSchema = [
      '@context' => 'https://schema.org',
      '@type' => 'BreadcrumbList',
      'itemListElement' => array_map(fn ($item, $i) => [
          '@type' => 'ListItem', 'position' => $i + 1, 'name' => (string) $item[0], 'item' => $item[1],
      ], $items, array_keys($items)),
  ];
@endphp
@push('schema')
  <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush

<section class="bg-white md:px-5 md:pt-5">
  <div class="relative isolate mx-auto max-w-[1440px] overflow-hidden rounded-b-[28px] bg-ink-soft text-white md:rounded-[36px]">
    {{-- Décor : grille très fine + halo (8 % max), comme le hero de l'accueil --}}
    <div class="pointer-events-none absolute inset-0 -z-10 opacity-[0.06] [background-image:linear-gradient(to_right,#fff_1px,transparent_1px),linear-gradient(to_bottom,#fff_1px,transparent_1px)] [background-size:64px_64px]" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -right-40 -top-40 -z-10 size-[620px] rounded-full bg-[radial-gradient(circle,color-mix(in_oklab,var(--color-primary)_8%,transparent)_0%,transparent_70%)]" aria-hidden="true"></div>

    <div class="mx-auto flex w-full max-w-[1200px] flex-col items-center px-6 pb-16 pt-14 text-center md:items-start md:px-12 md:pb-20 md:pt-16 md:text-left">
      {{-- Fil d'Ariane --}}
      <nav aria-label="Fil d’Ariane" class="reveal font-mono text-xs uppercase tracking-[0.08em] text-white/50" style="--i:0">
        <a href="{{ route('home') }}" class="rounded transition duration-200 hover:text-white">Accueil</a>
        <span class="mx-2" aria-hidden="true">/</span>
        @if ($crumb)
          {{ $crumb }}
        @else
          <span class="text-white/80" aria-current="page">{{ $badge ?? $title }}</span>
        @endif
      </nav>

      <h1 class="reveal display mt-6 max-w-[900px] text-[38px] leading-[1.05] text-white sm:text-[48px] md:text-[60px] md:leading-none lg:text-[68px]" style="--i:1">
        {{ $title }}
      </h1>

      @if ($intro)
        <p class="reveal mx-auto mt-6 max-w-[320px] text-base leading-[1.75] text-white/70 sm:max-w-[560px] md:mx-0 md:max-w-[620px] md:text-lg md:leading-[1.6]" style="--i:2">
          {{ $intro }}
        </p>
      @endif

      @isset($actions)
        <div class="reveal mt-9 flex flex-col items-center gap-4 sm:flex-row md:items-start" style="--i:3">
          {{ $actions }}
        </div>
      @endisset
    </div>
  </div>
</section>
