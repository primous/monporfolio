@php
$formRoute = config('site.form_route');
$formUrl = Route::has($formRoute) ? route($formRoute) : '#';

// [titre, texte, rotation ≥ md, position du fil (px depuis le haut) au-dessus de la carte]
$steps = [
    ['Vous me parlez de votre projet.', 'Cinq questions courtes, à votre rythme.', 'md:-rotate-2', 26],
    ['Je lis tout et je vous prépare une proposition.', 'Adaptée à votre situation, pas un modèle copié-collé.', 'md:rotate-1', 42],
    ['On en discute.', 'Vous posez vos questions, puis vous décidez.', 'md:-rotate-1', 26],
];
@endphp

<x-layout.section bg="surface">
  <div class="flex flex-col gap-8 md:flex-row md:items-end md:justify-between">
    <div class="reveal" style="--i:0">
      <x-ui.section-heading align="left" display badge="Comment ça marche" title="Trois étapes simples, et votre projet démarre" />
    </div>
    <div class="reveal shrink-0" style="--i:1">
      <x-ui.button :href="$formUrl">Remplir ma demande</x-ui.button>
    </div>
  </div>

  {{-- Cartes suspendues à un fil (≥ md) ; empilées sur mobile --}}
  <div class="relative mt-14">
    <svg class="pointer-events-none absolute inset-x-0 top-0 hidden h-20 w-full md:block" viewBox="0 0 1000 80" preserveAspectRatio="none" aria-hidden="true">
      <path d="M0 8 Q 500 76 1000 8" fill="none" stroke="currentColor" class="text-ink/25" stroke-width="1.5" vector-effect="non-scaling-stroke"/>
    </svg>

    <ol class="grid gap-5 md:grid-cols-3 md:gap-8">
      @foreach ($steps as $i => [$title, $text, $tilt, $wire])
        <li class="reveal relative md:pt-[var(--pt)]" style="--i:{{ $i + 1 }}; --pt: {{ $wire + 30 }}px">
          {{-- Ficelle + pince --}}
          <span class="absolute left-1/2 hidden w-px -translate-x-1/2 bg-ink/25 md:block" style="top: {{ $wire }}px; height: 30px" aria-hidden="true"></span>
          <span class="absolute left-1/2 hidden size-2.5 -translate-x-1/2 -translate-y-1/2 rounded-full bg-ink md:block" style="top: {{ $wire }}px" aria-hidden="true"></span>

          <article class="{{ $tilt }} origin-top rounded-[24px] border border-line bg-white p-6 shadow-float transition duration-300 ease-out hover:rotate-0 md:p-8">
            <div class="flex items-start justify-between">
              <span class="num-outline text-[64px] md:text-[80px]" aria-hidden="true">{{ sprintf('%02d', $i + 1) }}</span>
            </div>
            <h3 class="display mt-4 text-xl md:text-2xl"><span class="sr-only">Étape {{ $i + 1 }} : </span>{{ $title }}</h3>
            <p class="mt-3 text-base text-muted">{{ $text }}</p>
          </article>
        </li>
      @endforeach
    </ol>
  </div>
</x-layout.section>
