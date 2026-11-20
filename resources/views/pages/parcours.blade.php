<x-layouts::app
  title="Développeur web et closeur à Cotonou | Hounkpatin Primous"
  description="Développeur de formation et closeur certifié MarketingLab, Hounkpatin Primous crée à Cotonou des sites qui guident chaque visiteur jusqu’à l’action."
>
  @php
    $formUrl = route(config('site.form_route'));
    $photo = 'images/about/primous.jpg';
    $hasPhoto = file_exists(public_path($photo));

    // Ma façon de travailler : [titre, texte]
    $methode = [
        ['Comprendre', 'Je pars de votre activité, de vos clients et des questions qu’ils vous posent tous les jours.'],
        ['Clarifier', 'J’organise vos offres, vos prix et vos réponses sur une page claire, compréhensible en quelques secondes.'],
        ['Construire', 'Je conçois et je développe votre site, rapide et lisible sur téléphone comme sur ordinateur.'],
        ['Accompagner', 'Après la mise en ligne, je vous aide à faire venir des visiteurs sur votre site.'],
    ];
    $nbsp = fn (string $t) => str_replace([' ?', ' :', ' !'], ["\u{00A0}?", "\u{00A0}:", "\u{00A0}!"], $t);

    // Repères du parcours (config/site.php → 'parcours'). Liste vide => frise absente.
    $etapes = config('site.parcours', []);
  @endphp

  <x-layout.page-hero
    badge="Parcours"
    title="Du code à la vente : mon parcours."
    intro="Développeur de formation, closeur certifié : j’associe la rigueur du code à ce qui fait réellement vendre."
  >
    <x-slot:actions>
      <x-ui.button :href="$formUrl">Démarrer mon projet</x-ui.button>
    </x-slot:actions>
  </x-layout.page-hero>

  {{-- Le récit --}}
  <x-layout.section bg="white">
    <div class="grid gap-12 lg:grid-cols-[0.85fr_1.15fr] lg:gap-20">
      <div class="flex flex-col gap-8 lg:sticky lg:top-28 lg:self-start">
        <div class="reveal" style="--i:0">
          <x-ui.section-heading align="left" display badge="Mon histoire" title="Mon parcours a commencé dans le code." />
        </div>

        @if ($hasPhoto)
          <figure class="reveal-scale relative w-full max-w-[380px] overflow-hidden rounded-[24px] bg-ink-soft shadow-card" style="--i:1">
            <img src="{{ asset($photo) }}" alt="Portrait de Hounkpatin Primous" width="941" height="941" loading="lazy" class="block aspect-[4/5] w-full object-cover object-top">
            <figcaption class="absolute inset-x-4 bottom-4 rounded-2xl bg-white/95 px-4 py-3 shadow-card backdrop-blur">
              <span class="block font-heading font-bold text-ink">Hounkpatin Primous</span>
              <span class="text-sm text-muted">Développeur et closeur certifié</span>
            </figcaption>
          </figure>
        @endif
      </div>

      <div class="flex flex-col gap-6 text-base leading-[1.8] text-muted md:text-lg">
        <p class="reveal" style="--i:1">
          Avec une licence en systèmes informatiques et logiciels, j’ai passé mes premières années à construire la logique des sites&nbsp;: bases de données, architectures web, rigueur des algorithmes. Du solide, du précis.
        </p>
        <p class="reveal" style="--i:2">
          Mais j’ai vite remarqué un problème dont personne ne parlait. Des entrepreneurs investissaient beaucoup dans des sites magnifiques… qui ne leur apportaient presque aucun client. Le design était parfait. Le message, lui, était vide.
        </p>

        <blockquote class="reveal my-2 border-l-2 border-primary pl-6 font-heading text-2xl font-bold leading-snug tracking-tight text-ink md:text-[28px]" style="--i:2">
          Sans message clair, aucun site ne vend. Peu importe la qualité du code.
        </blockquote>

        <p class="reveal" style="--i:3">
          C’est ce constat qui m’a fait changer de trajectoire. J’ai intégré le <strong class="font-semibold text-ink">MarketingLab Institut</strong>, sous la direction de M.&nbsp;Beni Tossou&nbsp;: une formation exigeante, sans raccourci, centrée sur ce qui fait réellement grandir un chiffre d’affaires. J’en suis sorti <strong class="font-semibold text-ink">closeur certifié</strong>.
        </p>
        <p class="reveal" style="--i:3">
          Aujourd’hui, j’associe la rigueur du développeur à la force du copywriting, de la publicité Facebook et du closing. Mon approche est analytique avant d’être créative&nbsp;: votre site n’est pas seulement beau, il guide chaque visiteur jusqu’à l’action qui compte pour vous.
        </p>
      </div>
    </div>
  </x-layout.section>

  {{-- Repères du parcours (frise) --}}
  @if (! empty($etapes))
    <x-layout.section bg="surface">
      <div class="reveal" style="--i:0">
        <x-ui.section-heading align="left" display badge="Repères" title="Les étapes qui m’ont mené ici" />
      </div>

      <ol class="mt-12 grid gap-5 md:grid-cols-2 lg:grid-cols-4 md:gap-6">
        @foreach ($etapes as $i => $etape)
          <li class="reveal relative" style="--i:{{ min($i + 1, 3) }}">
            <article class="flex h-full flex-col rounded-[24px] border border-line bg-white p-6 shadow-card md:p-7">
              <div class="flex items-center gap-3">
                <span class="grid size-8 shrink-0 place-items-center rounded-full bg-ink font-mono text-xs font-medium text-white" aria-hidden="true">{{ sprintf('%02d', $i + 1) }}</span>
                <span class="font-mono text-xs font-medium uppercase tracking-[0.06em] text-muted">{{ $etape['annee'] ?? '' }}</span>
              </div>
              <h3 class="display mt-5 text-xl md:text-[22px]">{{ $etape['titre'] ?? '' }}</h3>
              @if (! empty($etape['texte']))
                <p class="mt-3 text-base text-muted">{{ $nbsp($etape['texte']) }}</p>
              @endif
            </article>
          </li>
        @endforeach
      </ol>
    </x-layout.section>
  @endif

  {{-- Ma façon de travailler --}}
  <x-layout.section :bg="empty($etapes) ? 'surface' : 'white'">
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
