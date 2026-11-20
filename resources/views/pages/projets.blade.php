<x-layouts::app
  title="Réalisations web au Bénin : sites et plateformes | Primous"
  description="Découvrez les sites conçus et développés par Hounkpatin Primous au Bénin : plateformes, pages de présentation et outils en ligne clairs et efficaces."
>
  <x-layout.page-hero
    badge="Projets"
    title="Les projets que j’ai conçus et développés."
    intro="Chaque site part de la même question : qu’est-ce que le visiteur doit comprendre en quelques secondes ? Voici les réponses que j’y ai apportées."
  />

  {{-- Les projets (admin : Contenu du site → Projets), en alternance gauche / droite --}}
  <x-layout.section bg="white">
    @if ($projets->isNotEmpty())
      <ol class="flex flex-col gap-16 md:gap-24">
        @foreach ($projets as $i => $projet)
          @php
            $dims = $projet->visuelDimensions();
            $inverse = $i % 2 === 1;
            // Pas de doublon : le résumé n'est affiché que s'il diffère de la description.
            $resumeDistinct = filled($projet->resume) && rtrim($projet->resume, '. ') !== rtrim($projet->description, '. ');
          @endphp
          <li class="grid items-center gap-8 md:grid-cols-[1.25fr_1fr] md:gap-12 lg:gap-16">
            @if ($projet->visuelUrl() && $dims)
              {{-- Capture entière, dans un cadre de navigateur --}}
              <div @class(['reveal-scale', 'md:order-2' => $inverse])>
                <x-ui.browser-frame :src="$projet->visuelUrl()" :alt="'Aperçu du projet '.$projet->titre" :width="$dims[0]" :height="$dims[1]" class="shadow-float" />
              </div>
            @endif

            <div @class(['reveal flex flex-col items-start', 'md:order-1' => $inverse])>
              <span class="font-mono text-sm font-medium text-muted">{{ sprintf('%02d', $i + 1) }} / {{ sprintf('%02d', $projets->count()) }}</span>
              <h2 class="display mt-3 text-[30px] md:text-[40px]">{{ $projet->titre }}</h2>
              @if ($resumeDistinct)
                <p class="mt-3 font-medium text-ink">{{ $projet->resume }}</p>
              @endif
              <p class="mt-3 text-base text-muted">{{ $projet->description }}</p>

              @if ($projet->lien)
                <a href="{{ $projet->lien }}" target="_blank" rel="noopener" class="mt-6 inline-flex items-center gap-2 rounded font-semibold text-ink underline decoration-primary decoration-2 underline-offset-4 transition duration-200 hover:text-primary">
                  Voir le site en ligne
                  <svg viewBox="0 0 24 24" class="size-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7M17 7H9M17 7v8"/></svg>
                  <span class="sr-only">(nouvel onglet)</span>
                </a>
              @endif
            </div>
          </li>
        @endforeach
      </ol>
    @else
      <x-ui.empty-state class="reveal" title="Les projets arrivent ici." text="Revenez bientôt, ou décrivez-moi le vôtre : il pourrait être le prochain.">
        <x-ui.button :href="route(config('site.form_route'))">Démarrer mon projet</x-ui.button>
      </x-ui.empty-state>
    @endif
  </x-layout.section>

  <x-sections.cta />
</x-layouts::app>
