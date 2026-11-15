<x-layouts::app
  title="Avis clients | Primous"
  description="Ce qu’en disent les personnes pour qui j’ai construit un site. Avis publiés avec l’accord de chaque client."
>
  <x-layout.page-hero
    badge="Avis"
    title="Ce qu’en disent mes clients."
    intro="Des retours de personnes pour qui j’ai construit un site, publiés avec leur accord."
  />

  {{-- Avis publiés (admin : Contenu du site → Avis clients, statut « Publié ») --}}
  <x-layout.section bg="white">
    @if ($avis->isNotEmpty())
      <ul class="columns-1 gap-5 md:columns-2 md:gap-6 lg:columns-3">
        @foreach ($avis as $i => $a)
          <li class="reveal mb-5 break-inside-avoid md:mb-6" style="--i:{{ min($i % 3 + 1, 3) }}">
            <figure class="flex flex-col gap-5 rounded-[24px] border border-line bg-white p-6 shadow-card md:p-8">
              <svg viewBox="0 0 24 24" class="size-8 text-primary" fill="currentColor" aria-hidden="true">
                <path d="M9.5 6C6.5 7 4.5 9.6 4.5 13v5h5.5v-6H7.3c.2-1.9 1.4-3.3 3-4l-.8-2zm9 0c-3 1-5 3.6-5 7v5H19v-6h-2.7c.2-1.9 1.4-3.3 3-4l-.8-2z"/>
              </svg>
              <blockquote class="text-base text-ink md:text-lg">« {{ $a->contenu }} »</blockquote>
              <figcaption class="flex items-center gap-3 border-t border-line pt-5">
                <span class="grid size-10 shrink-0 place-items-center rounded-full bg-surface font-heading text-sm font-bold text-ink ring-1 ring-line" aria-hidden="true">{{ mb_strtoupper(mb_substr($a->nom, 0, 1)) }}</span>
                <span class="leading-tight">
                  <span class="block font-semibold text-ink">{{ $a->nom }}</span>
                  @if ($a->activite)
                    <span class="text-sm text-muted">{{ $a->activite }}</span>
                  @endif
                </span>
              </figcaption>
            </figure>
          </li>
        @endforeach
      </ul>
    @else
      <x-ui.empty-state class="reveal"
        title="Les avis de mes clients seront publiés ici."
        text="Uniquement de vrais retours, avec l’accord de chaque client. En attendant, voyez ce que j’ai déjà construit."
      >
        <x-ui.button variant="ghost" :href="route('projets')" :icon="false">Voir les projets</x-ui.button>
      </x-ui.empty-state>
    @endif
  </x-layout.section>

  <x-sections.cta />
</x-layouts::app>
