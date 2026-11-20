<x-layouts::app
  title="Services web à Cotonou : site, copywriting, Facebook Ads"
  description="Site vitrine, landing page, copywriting, Facebook Ads et contenus à Cotonou : des outils pensés pour transformer vos visiteurs en clients au Bénin."
>
  @php
    $formUrl = route(config('site.form_route'));
  @endphp

  <x-layout.page-hero
    badge="Services"
    title="Un site pensé pour votre activité, pas un modèle."
    intro="À Cotonou comme à distance, chaque offre part de la même idée : expliquer clairement ce que vous faites, une seule fois, pour que vos clients vous écrivent prêts à acheter."
  >
    <x-slot:actions>
      <x-ui.button :href="$formUrl">Démarrer mon projet</x-ui.button>
    </x-slot:actions>
  </x-layout.page-hero>

  {{-- Les offres (admin : Contenu du site → Services) --}}
  <x-layout.section bg="white">
    <div class="reveal" style="--i:0">
      <x-ui.section-heading align="left" display title="Ce que je peux construire pour vous" subtitle="Choisissez un point de départ. Je l’adapte ensuite à votre situation, dans une proposition claire." />
    </div>

    @if ($services->isNotEmpty())
      <ol class="mt-12 grid gap-5 md:grid-cols-2 lg:grid-cols-3 md:gap-6">
        @foreach ($services as $i => $service)
          <li class="reveal" style="--i:{{ min($i % 3 + 1, 3) }}">
            <article class="spotlight flex h-full flex-col rounded-[24px] border border-line bg-white p-6 shadow-card transition duration-300 ease-out hover:-translate-y-1 hover:border-primary/30 hover:shadow-float md:p-8">
              <span class="font-mono text-sm font-medium text-muted">{{ sprintf('%02d', $i + 1) }}</span>
              <h3 class="display mt-4 text-2xl md:text-[28px]">{{ $service->titre }}</h3>
              <p class="mt-3 text-base text-muted">{{ $service->description }}</p>

              @if (! empty($service->points))
                <ul class="mt-6 flex flex-col gap-3 border-t border-line pt-6">
                  @foreach ($service->points as $point)
                    <li class="flex items-start gap-3 text-[15px] font-medium text-ink">
                      <svg viewBox="0 0 24 24" class="mt-0.5 size-5 shrink-0" aria-hidden="true"><circle cx="12" cy="12" r="12" class="fill-primary"/><path d="M7.5 12.5l3 3 6-6.5" fill="none" class="stroke-white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                      {{ $point }}
                    </li>
                  @endforeach
                </ul>
              @endif
            </article>
          </li>
        @endforeach
      </ol>
    @else
      <x-ui.empty-state class="reveal mt-12"
        title="Les offres détaillées arrivent ici."
        text="En attendant, décrivez votre projet en cinq questions : je vous réponds avec une proposition adaptée à votre activité."
      >
        <x-ui.button :href="$formUrl">Démarrer mon projet</x-ui.button>
      </x-ui.empty-state>
    @endif
  </x-layout.section>

  <x-sections.process />
  <x-sections.home-faq bg="white" />
  <x-sections.cta />
</x-layouts::app>
