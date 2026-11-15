@props([
    'eyebrow' => 'Disponible pour de nouveaux projets',
    'title' => 'Des sites internet clairs qui transforment vos visiteurs en clients.',
    'subtitle' => 'Je conçois et développe des sites rapides, élégants et faciles à comprendre, pour que vos clients sachent quoi faire dès la première visite.',
    'ctaPrimary' => ['label' => 'Démarrer un projet', 'href' => '#contact'],
    'ctaSecondary' => ['label' => 'Voir les réalisations', 'href' => '#realisations'],
    'proofs' => ['Design sur mesure', 'Pensé mobile d’abord', 'Premier échange gratuit'],
])
@php
// Collage : [colonne => [[fichier, alt, classe hauteur], ...]]
$columns = [
    [
        ['hero-1.jpg', 'Réalisation : page d’accueil de la plateforme événementielle Evenzia', 'h-[200px] md:h-[237px]'],
        ['hero-2.jpg', 'Réalisation : page d’accueil du site de récits Vécu', 'h-[140px] md:h-[170px]'],
    ],
    [
        ['hero-3.jpg', 'Réalisation : page d’accueil du site d’activités pour enfants Petits Pas', 'h-[250px] md:h-[300px]'],
    ],
    [
        ['hero-4.jpg', 'Réalisation : page d’accueil de l’outil de sondages anonymes AnonyVox', 'h-[250px] md:h-[300px]'],
    ],
    [
        ['hero-6.jpg', 'Réalisation : page d’accueil de la plateforme de recrutement Talent Club', 'h-[200px] md:h-[237px]'],
        ['hero-5.jpg', 'Réalisation : page d’accueil d’un site de rencontres sérieuses', 'h-[140px] md:h-[170px]'],
    ],
];
$tile = 0;
@endphp

<section id="realisations" class="relative isolate overflow-hidden bg-white pb-[50px] pt-[60px] md:pt-[120px]">
  {{-- Halo radial violet décoratif (opacité max 8 %) --}}
  <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 flex justify-center" aria-hidden="true">
    <div class="h-[560px] w-full max-w-[1000px] bg-[radial-gradient(ellipse_at_center,color-mix(in_oklab,var(--color-primary)_8%,transparent)_0%,transparent_70%)]"></div>
  </div>

  <div class="mx-auto flex w-full max-w-[1200px] flex-col items-center px-6 text-center md:px-12">
    <div class="reveal" style="--i:0" x-intersect.once="$el.classList.add('is-visible')">
      <x-ui.badge>{{ $eyebrow }}</x-ui.badge>
    </div>

    <h1 class="reveal mt-6 max-w-[839px] text-[32px] md:text-[48px] lg:text-[56px]" style="--i:1" x-intersect.once="$el.classList.add('is-visible')">
      {{ $title }}
    </h1>

    <p class="reveal mt-5 max-w-[619px] text-base text-muted md:text-lg" style="--i:2" x-intersect.once="$el.classList.add('is-visible')">
      {{ $subtitle }}
    </p>

    <div class="reveal mt-8 flex w-full flex-col items-center gap-3 sm:w-auto sm:flex-row" style="--i:3" x-intersect.once="$el.classList.add('is-visible')">
      <x-ui.button :href="$ctaPrimary['href']" class="w-full justify-center sm:w-auto">{{ $ctaPrimary['label'] }}</x-ui.button>
      <x-ui.button variant="ghost" :href="$ctaSecondary['href']" class="w-full justify-center sm:w-auto">{{ $ctaSecondary['label'] }}</x-ui.button>
    </div>
  </div>

  {{-- Collage 4 colonnes aligné en bas --}}
  <div class="mx-auto mt-14 w-full max-w-[1400px] px-6 md:mt-16 md:px-12">
    {{-- Proportions 250/360/360/250 en fr : jamais de débordement, largeur nominale atteinte à max-w-[1280px] --}}
    <div class="mx-auto grid max-w-[1280px] grid-cols-1 items-end justify-center gap-4 sm:grid-cols-2 md:gap-5 lg:grid-cols-[250fr_360fr_360fr_250fr]">
      @foreach ($columns as $column)
        <div class="flex flex-col gap-4 md:gap-5">
          @foreach ($column as [$file, $alt, $height])
            <div class="reveal-scale" style="--i:{{ $tile++ }}" x-intersect.once="$el.classList.add('is-visible')">
              <x-ui.image-tile
                :src="asset('images/hero/'.$file)"
                :alt="$alt"
                width="1024"
                height="463"
                :lazy="false"
                img-class="object-left-top"
                :class="$height"
              />
            </div>
          @endforeach
        </div>
      @endforeach
    </div>
  </div>

  {{-- Barre de confiance --}}
  <div class="reveal mx-auto mt-12 flex w-full max-w-[1200px] flex-col items-start gap-8 px-6 sm:items-center md:mt-14 md:flex-row md:justify-center md:gap-12 md:px-12" style="--i:2" x-intersect.once="$el.classList.add('is-visible')">
    @foreach ($proofs as $proof)
      <x-ui.check-item>{{ $proof }}</x-ui.check-item>
    @endforeach
  </div>
</section>
