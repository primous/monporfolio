<x-layouts::app
  :title="$active ? $active->nom.' : articles sur le site internet | Primous' : 'Blog : conseils pour un site internet qui vend | Primous'"
  description="Conseils concrets pour créer un site internet qui vend au Bénin : parcours client, réassurance, formulaires et exemples par activité. Articles courts."
>
  @php
    // Nombre à la française (espace fine insécable entre les milliers) + accord singulier / pluriel.
    $nb = fn (int $n) => number_format($n, 0, ',', "\u{202F}");
    $mot = fn (int $n, string $un, string $plusieurs) => $n > 1 ? $plusieurs : $un;
  @endphp

  <x-layout.page-hero
    badge="Blog"
    title="Conseils pour un site qui se comprend tout seul."
    intro="Des articles courts et concrets pour présenter votre activité, rassurer vos clients et recevoir plus de demandes."
  />

  <x-layout.section bg="white">
    @if ($stats['articles'] > 0)
      {{-- Statistiques réelles du blog --}}
      <dl class="reveal grid grid-cols-3 divide-x divide-line rounded-[24px] border border-line bg-surface" style="--i:0">
        @foreach ([
          [$stats['articles'], $mot($stats['articles'], 'article', 'articles')],
          [$stats['vues'], $mot($stats['vues'], 'lecture', 'lectures')],
          [$stats['commentaires'], $mot($stats['commentaires'], 'commentaire', 'commentaires')],
        ] as [$valeur, $libelle])
          <div class="flex flex-col items-center gap-1 px-3 py-5 text-center md:py-6">
            <dd class="display text-[28px] text-ink md:text-[40px]">{{ $nb($valeur) }}</dd>
            <dt class="font-mono text-[11px] font-medium uppercase tracking-[0.06em] text-muted md:text-xs">{{ $libelle }}</dt>
          </div>
        @endforeach
      </dl>

      {{-- Filtre par catégorie --}}
      @if ($categories->isNotEmpty())
        <nav class="reveal mt-10 flex flex-wrap gap-2" aria-label="Catégories du blog" style="--i:1">
          <a href="{{ route('blog') }}" @if (! $active) aria-current="page" @endif
             @class(['rounded-full px-4 py-2 text-sm font-medium transition duration-200',
                     'bg-ink text-white' => ! $active,
                     'border border-line bg-white text-ink hover:border-ink' => $active])>
            Tous <span class="ml-1 font-mono text-xs opacity-60">{{ $stats['articles'] }}</span>
          </a>
          @foreach ($categories as $categorie)
            @php $estActive = $active?->is($categorie); @endphp
            <a href="{{ route('blog', ['categorie' => $categorie->slug]) }}" @if ($estActive) aria-current="page" @endif
               @class(['rounded-full px-4 py-2 text-sm font-medium transition duration-200',
                       'bg-ink text-white' => $estActive,
                       'border border-line bg-white text-ink hover:border-ink' => ! $estActive])>
              {{ $categorie->nom }} <span class="ml-1 font-mono text-xs opacity-60">{{ $categorie->articles_count }}</span>
            </a>
          @endforeach
        </nav>
      @endif
    @endif

    {{-- Articles publiés (admin : Contenu du site → Articles du blog) --}}
    @if ($articles->isNotEmpty())
      <ul class="mt-10 grid gap-5 md:grid-cols-2 lg:grid-cols-3 md:gap-6">
        @foreach ($articles as $i => $article)
          <li class="reveal" style="--i:{{ min($i % 3 + 1, 3) }}">
            <a href="{{ route('blog.article', $article->slug) }}" class="group flex h-full flex-col rounded-[24px] border border-line bg-white p-6 shadow-card transition duration-300 ease-out hover:-translate-y-1 hover:border-primary/30 hover:shadow-float md:p-8">
              <div class="flex flex-wrap items-center gap-x-3 gap-y-2">
                @if ($article->categorie)
                  <span class="rounded-full bg-primary/10 px-2.5 py-0.5 font-mono text-[11px] font-medium uppercase tracking-[0.06em] text-primary">{{ $article->categorie->nom }}</span>
                @endif
                @if ($article->date_publication)
                  <time datetime="{{ $article->date_publication->toDateString() }}" class="font-mono text-xs text-muted">{{ $article->date_publication->translatedFormat('j F Y') }}</time>
                @endif
              </div>

              <h2 class="display mt-4 text-2xl transition duration-200 group-hover:text-primary">{{ $article->titre }}</h2>
              <p class="mt-3 flex-1 text-base text-muted">{{ $article->resume() }}</p>

              <div class="mt-6 flex items-center justify-between gap-4 border-t border-line pt-5">
                <span class="inline-flex items-center gap-2 font-semibold text-ink">
                  Lire l’article
                  <svg viewBox="0 0 24 24" class="size-4 transition duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </span>
                {{-- Lectures, commentaires, temps de lecture --}}
                <span class="flex items-center gap-3 font-mono text-xs text-muted">
                  <span class="inline-flex items-center gap-1" title="{{ $nb($article->vues) }} {{ $mot($article->vues, 'lecture', 'lectures') }}">
                    <svg viewBox="0 0 24 24" class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $nb($article->vues) }}<span class="sr-only"> {{ $mot($article->vues, 'lecture', 'lectures') }}</span>
                  </span>
                  <span class="inline-flex items-center gap-1" title="{{ $article->commentaires_publies_count }} {{ $mot($article->commentaires_publies_count, 'commentaire', 'commentaires') }}">
                    <svg viewBox="0 0 24 24" class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                    {{ $article->commentaires_publies_count }}<span class="sr-only"> {{ $mot($article->commentaires_publies_count, 'commentaire', 'commentaires') }}</span>
                  </span>
                  <span>{{ $article->minutesDeLecture() }} min</span>
                </span>
              </div>
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
      <x-ui.empty-state class="reveal mt-10"
        title="Les premiers articles arrivent ici."
        text="Des conseils concrets pour présenter votre activité en ligne. En attendant, une question sur votre projet ? Écrivez-moi."
      >
        <x-ui.button :href="route(config('site.form_route'))">Démarrer mon projet</x-ui.button>
      </x-ui.empty-state>
    @endif
  </x-layout.section>

  <x-sections.cta />
</x-layouts::app>
