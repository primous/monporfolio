<x-layouts::app
  :title="$article->titre.' | Primous'"
  :description="$article->resume(155)"
>
  <x-layout.page-hero :title="$article->titre" :intro="$article->extrait">
    <x-slot:crumb>
      <a href="{{ route('blog') }}" class="rounded transition duration-200 hover:text-white">Blog</a>
    </x-slot:crumb>
    <x-slot:actions>
      <p class="font-mono text-xs font-medium uppercase tracking-[0.06em] text-white/60">
        @if ($article->date_publication)
          <time datetime="{{ $article->date_publication->toDateString() }}">{{ $article->date_publication->translatedFormat('j F Y') }}</time>
          <span aria-hidden="true"> · </span>
        @endif
        {{ $article->minutesDeLecture() }} min de lecture
      </p>
    </x-slot:actions>
  </x-layout.page-hero>

  <x-layout.section bg="white">
    {{-- Contenu saisi dans l'admin (éditeur Filament), réservé à l'administrateur --}}
    <article class="prose-article mx-auto max-w-[720px]">
      {!! $article->contenu !!}
    </article>

    <div class="mx-auto mt-12 flex max-w-[720px] flex-col items-start gap-6 border-t border-line pt-8 sm:flex-row sm:items-center sm:justify-between">
      <a href="{{ route('blog') }}" class="inline-flex items-center gap-2 rounded font-semibold text-ink transition duration-200 hover:text-primary">
        <svg viewBox="0 0 24 24" class="size-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
        Tous les articles
      </a>
      <x-ui.button :href="route(config('site.form_route'))">Démarrer mon projet</x-ui.button>
    </div>
  </x-layout.section>

  @if ($autres->isNotEmpty())
    <x-layout.section bg="surface">
      <h2 class="display text-[28px] md:text-[36px]">À lire aussi</h2>
      <ul class="mt-8 grid gap-5 md:grid-cols-2 md:gap-6">
        @foreach ($autres as $autre)
          <li>
            <a href="{{ route('blog.article', $autre->slug) }}" class="group flex h-full flex-col rounded-[24px] border border-line bg-white p-6 shadow-card transition duration-300 ease-out hover:-translate-y-1 hover:border-primary/30 md:p-8">
              <span class="display text-xl transition duration-200 group-hover:text-primary md:text-2xl">{{ $autre->titre }}</span>
              <span class="mt-2 text-base text-muted">{{ $autre->resume(140) }}</span>
            </a>
          </li>
        @endforeach
      </ul>
    </x-layout.section>
  @endif

  <x-sections.cta />
</x-layouts::app>
