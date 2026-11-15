<x-layouts::app
  title="Blog : conseils pour un site clair | Primous"
  description="Des articles courts et concrets pour présenter votre activité, rassurer vos clients et recevoir plus de demandes."
>
  <x-layout.page-hero
    badge="Blog"
    title="Conseils pour un site qui se comprend tout seul."
    intro="Des articles courts et concrets pour présenter votre activité, rassurer vos clients et recevoir plus de demandes."
  />

  {{-- Articles publiés (admin : Contenu du site → Articles du blog) --}}
  <x-layout.section bg="white">
    @if ($articles->isNotEmpty())
      <ul class="grid gap-5 md:grid-cols-2 lg:grid-cols-3 md:gap-6">
        @foreach ($articles as $i => $article)
          <li class="reveal" style="--i:{{ min($i % 3 + 1, 3) }}">
            <a href="{{ route('blog.article', $article->slug) }}" class="group flex h-full flex-col rounded-[24px] border border-line bg-white p-6 shadow-card transition duration-300 ease-out hover:-translate-y-1 hover:border-primary/30 hover:shadow-float md:p-8">
              <p class="font-mono text-xs font-medium uppercase tracking-[0.06em] text-muted">
                @if ($article->date_publication)
                  <time datetime="{{ $article->date_publication->toDateString() }}">{{ $article->date_publication->translatedFormat('j F Y') }}</time>
                  <span aria-hidden="true"> · </span>
                @endif
                {{ $article->minutesDeLecture() }} min de lecture
              </p>
              <h2 class="display mt-4 text-2xl transition duration-200 group-hover:text-primary">{{ $article->titre }}</h2>
              <p class="mt-3 flex-1 text-base text-muted">{{ $article->resume() }}</p>
              <span class="mt-6 inline-flex items-center gap-2 font-semibold text-ink">
                Lire l’article
                <svg viewBox="0 0 24 24" class="size-4 transition duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
              </span>
            </a>
          </li>
        @endforeach
      </ul>

      {{-- Pagination --}}
      @if ($articles->hasPages())
        <nav class="mt-12 flex items-center justify-center gap-3" aria-label="Pages du blog">
          @if ($articles->onFirstPage())
            <span class="rounded-full border border-line px-5 py-2.5 text-sm font-semibold text-muted opacity-50">Précédent</span>
          @else
            <a href="{{ $articles->previousPageUrl() }}" class="rounded-full border border-line px-5 py-2.5 text-sm font-semibold text-ink transition duration-200 hover:border-primary hover:text-primary">Précédent</a>
          @endif
          <span class="font-mono text-sm text-muted">{{ $articles->currentPage() }} / {{ $articles->lastPage() }}</span>
          @if ($articles->hasMorePages())
            <a href="{{ $articles->nextPageUrl() }}" class="rounded-full border border-line px-5 py-2.5 text-sm font-semibold text-ink transition duration-200 hover:border-primary hover:text-primary">Suivant</a>
          @else
            <span class="rounded-full border border-line px-5 py-2.5 text-sm font-semibold text-muted opacity-50">Suivant</span>
          @endif
        </nav>
      @endif
    @else
      <x-ui.empty-state class="reveal"
        title="Les premiers articles arrivent ici."
        text="Des conseils concrets pour présenter votre activité en ligne. En attendant, une question sur votre projet ? Écrivez-moi."
      >
        <x-ui.button :href="route(config('site.form_route'))">Démarrer mon projet</x-ui.button>
      </x-ui.empty-state>
    @endif
  </x-layout.section>

  <x-sections.cta />
</x-layouts::app>
