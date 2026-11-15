<x-layouts::app
  title="Parcours : qui je suis et comment je travaille | Primous"
  description="Hounkpatin Primous, créateur de sites internet. Ma façon de travailler : comprendre, clarifier, construire, accompagner."
>
  @php
    $formUrl = route(config('site.form_route'));

    // Ma façon de travailler : [titre, texte]
    $methode = [
        ['Comprendre', 'Je pars de votre activité, de vos clients et des questions qu’ils vous posent tous les jours.'],
        ['Clarifier', 'J’organise vos offres, vos prix et vos réponses sur une page claire, compréhensible en quelques secondes.'],
        ['Construire', 'Je conçois et je développe votre site, rapide et lisible sur téléphone comme sur ordinateur.'],
        ['Accompagner', 'Après la mise en ligne, je vous aide à faire venir des visiteurs sur votre site.'],
    ];
    $nbsp = fn (string $t) => str_replace([' ?', ' :'], ["\u{00A0}?", "\u{00A0}:"], $t);

    // Étapes du parcours (config/site.php → 'parcours'). Liste vide => section absente.
    $etapes = config('site.parcours', []);
  @endphp

  <x-layout.page-hero
    badge="Parcours"
    title="Qui je suis, et comment je travaille."
    intro="Un seul interlocuteur, du premier échange à la mise en ligne. Vous savez toujours qui lit votre demande et qui construit votre site."
  >
    <x-slot:actions>
      <x-ui.button :href="$formUrl">Démarrer mon projet</x-ui.button>
    </x-slot:actions>
  </x-layout.page-hero>

  {{-- Présentation (même bannière que l'accueil) --}}
  <x-sections.about />

  {{-- Ma façon de travailler --}}
  <x-layout.section bg="surface">
    <div class="reveal" style="--i:0">
      <x-ui.section-heading align="left" display badge="Méthode" title="Ma façon de travailler" />
    </div>

    <ol class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4 md:gap-6">
      @foreach ($methode as $i => [$titre, $texte])
        <li class="reveal" style="--i:{{ min($i + 1, 3) }}">
          <article class="flex h-full flex-col rounded-[24px] border border-line bg-white p-6 shadow-card md:p-7">
            <span class="num-outline text-[56px]" aria-hidden="true">{{ sprintf('%02d', $i + 1) }}</span>
            <h3 class="display mt-4 text-xl md:text-2xl"><span class="sr-only">Étape {{ $i + 1 }} : </span>{{ $titre }}</h3>
            <p class="mt-3 text-base text-muted">{{ $nbsp($texte) }}</p>
          </article>
        </li>
      @endforeach
    </ol>
  </x-layout.section>

  {{-- Repères du parcours : affichés seulement s'ils sont renseignés (config/site.php) --}}
  @if (! empty($etapes))
    <x-layout.section bg="white">
      <div class="reveal" style="--i:0">
        <x-ui.section-heading align="left" display badge="Repères" title="Mon parcours" />
      </div>
      <ol class="mt-12 border-l border-line pl-6 md:pl-10">
        @foreach ($etapes as $i => $etape)
          <li class="reveal relative pb-10 last:pb-0" style="--i:{{ min($i + 1, 3) }}">
            <span class="absolute -left-[31px] top-1.5 size-3 rounded-full bg-primary ring-4 ring-white md:-left-[47px]" aria-hidden="true"></span>
            <p class="font-mono text-sm font-medium text-muted">{{ $etape['annee'] ?? '' }}</p>
            <h3 class="display mt-1 text-xl md:text-2xl">{{ $etape['titre'] ?? '' }}</h3>
            @if (! empty($etape['texte']))
              <p class="mt-2 max-w-[640px] text-base text-muted">{{ $etape['texte'] }}</p>
            @endif
          </li>
        @endforeach
      </ol>
    </x-layout.section>
  @endif

  {{-- Quelques projets --}}
  @if ($projets->isNotEmpty())
    <x-layout.section :bg="empty($etapes) ? 'white' : 'surface'">
      <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
        <div class="reveal" style="--i:0">
          <x-ui.section-heading align="left" display badge="Réalisations" title="Ce que j’ai déjà construit" />
        </div>
        <a href="{{ route('projets') }}" class="reveal inline-flex shrink-0 items-center gap-2 rounded font-semibold text-ink underline decoration-primary decoration-2 underline-offset-4 transition duration-200 hover:text-primary" style="--i:1">
          Voir tous les projets
          <svg viewBox="0 0 24 24" class="size-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </div>

      <ul class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3 md:gap-6">
        @foreach ($projets as $i => $projet)
          @php $dims = $projet->visuelDimensions(); @endphp
          <li class="reveal" style="--i:{{ min($i + 1, 3) }}">
            <a href="{{ route('projets') }}" class="group flex h-full flex-col rounded-[24px] border border-line bg-white p-3 shadow-card transition duration-300 ease-out hover:-translate-y-1 hover:border-primary/30 hover:shadow-float">
              @if ($projet->visuelUrl() && $dims)
                <x-ui.browser-frame :src="$projet->visuelUrl()" :alt="'Aperçu du projet '.$projet->titre" :width="$dims[0]" :height="$dims[1]" />
              @endif
              <span class="px-3 pb-3 pt-4">
                <span class="block font-heading text-lg font-bold text-ink">{{ $projet->titre }}</span>
                @if ($projet->resume)
                  <span class="mt-1 block text-sm text-muted">{{ $projet->resume }}</span>
                @endif
              </span>
            </a>
          </li>
        @endforeach
      </ul>
    </x-layout.section>
  @endif

  <x-sections.cta />
</x-layouts::app>
