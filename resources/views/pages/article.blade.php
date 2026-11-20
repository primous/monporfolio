<x-layouts::app
  :title="mb_strlen($article->titre) <= 50 ? $article->titre.' | Primous' : $article->titre"
  :description="$article->resume(155)"
  :canonical="route('blog.article', $article->slug)"
  og-type="article"
>
  @php
    // Données structurées de l'article (BlogPosting)
    $articleSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'mainEntityOfPage' => route('blog.article', $article->slug),
        'headline' => $article->titre,
        'description' => $article->resume(155),
        'image' => asset('images/brand/og-default.jpg'),
        'datePublished' => $article->date_publication?->toAtomString(),
        'dateModified' => $article->updated_at?->toAtomString(),
        'inLanguage' => 'fr',
        'wordCount' => str_word_count(strip_tags($article->contenu)),
        'articleSection' => $article->categorie?->nom,
        'author' => ['@type' => 'Person', 'name' => 'Hounkpatin Primous', 'url' => route('parcours')],
        'publisher' => ['@id' => url('/').'/#entreprise'],
        'interactionStatistic' => [
            ['@type' => 'InteractionCounter', 'interactionType' => 'https://schema.org/ReadAction', 'userInteractionCount' => (int) $article->vues],
            ['@type' => 'InteractionCounter', 'interactionType' => 'https://schema.org/CommentAction', 'userInteractionCount' => $commentaires->count()],
        ],
    ];
    $fil = array_values(array_filter([
        ['Accueil', route('home')],
        ['Blog', route('blog')],
        $article->categorie ? [$article->categorie->nom, route('blog', ['categorie' => $article->categorie->slug])] : null,
        [$article->titre, route('blog.article', $article->slug)],
    ]));
  @endphp
  @push('schema')
    <script type="application/ld+json">{!! json_encode(array_filter($articleSchema), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
  @endpush

  @php
    $nb = fn (int $n) => number_format($n, 0, ',', "\u{202F}");
    $mot = fn (int $n, string $un, string $plusieurs) => $n > 1 ? $plusieurs : $un;

    // Liens de partage (adresse canonique de l'article)
    $url = route('blog.article', $article->slug);
    $partage = [
        'facebook' => 'https://www.facebook.com/sharer/sharer.php?u='.urlencode($url),
        'whatsapp' => 'https://wa.me/?text='.rawurlencode($article->titre.' — '.$url),
    ];
    $nbCommentaires = $commentaires->count();
  @endphp

  <x-layout.page-hero :title="$article->titre" :intro="$article->extrait" :breadcrumbs="$fil">
    <x-slot:crumb>
      <a href="{{ route('blog') }}" class="rounded transition duration-200 hover:text-white">Blog</a>
      @if ($article->categorie)
        <span class="mx-2" aria-hidden="true">/</span>
        <a href="{{ route('blog', ['categorie' => $article->categorie->slug]) }}" class="rounded transition duration-200 hover:text-white">{{ $article->categorie->nom }}</a>
      @endif
    </x-slot:crumb>
    <x-slot:actions>
      <p class="flex flex-wrap items-center justify-center gap-x-3 gap-y-1 font-mono text-xs font-medium uppercase tracking-[0.06em] text-white/60 md:justify-start">
        @if ($article->date_publication)
          <time datetime="{{ $article->date_publication->toDateString() }}">{{ $article->date_publication->translatedFormat('j F Y') }}</time>
          <span aria-hidden="true">·</span>
        @endif
        <span>{{ $article->minutesDeLecture() }} min de lecture</span>
        <span aria-hidden="true">·</span>
        <span>{{ $nb($article->vues) }} {{ $mot($article->vues, 'lecture', 'lectures') }}</span>
        <span aria-hidden="true">·</span>
        <a href="#commentaires" class="rounded transition duration-200 hover:text-white">{{ $nbCommentaires }} {{ $mot($nbCommentaires, 'commentaire', 'commentaires') }}</a>
      </p>
    </x-slot:actions>
  </x-layout.page-hero>

  <x-layout.section bg="white">
    {{-- Contenu saisi dans l'admin (éditeur Filament), nettoyé à l'affichage :
         aucun script, attribut on* ni lien javascript: même si un compte admin était compromis --}}
    <article class="prose-article mx-auto max-w-[720px]">
      {!! \Illuminate\Support\Str::sanitizeHtml($article->contenu) !!}
    </article>

    {{-- Partage --}}
    <div class="mx-auto mt-12 max-w-[720px] border-t border-line pt-8"
         x-data="{ copie: false, async copier() { try { await navigator.clipboard.writeText(@js($url)); } catch (e) { const t = document.createElement('textarea'); t.value = @js($url); document.body.appendChild(t); t.select(); document.execCommand('copy'); t.remove(); } this.copie = true; setTimeout(() => this.copie = false, 2500); } }">
      <p class="font-mono text-xs font-medium uppercase tracking-[0.06em] text-muted">Partager cet article</p>
      <div class="mt-4 flex flex-wrap gap-3">
        <a href="{{ $partage['facebook'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full border border-line bg-white px-4 py-2.5 text-sm font-semibold text-ink transition duration-200 hover:border-primary hover:text-primary">
          <svg viewBox="0 0 24 24" class="size-4" fill="currentColor" aria-hidden="true"><path d="M13.5 21v-7.5h2.53l.38-2.94H13.5V8.7c0-.85.24-1.43 1.46-1.43h1.56V4.64A20.9 20.9 0 0014.25 4.5c-2.25 0-3.79 1.37-3.79 3.9v2.16H7.92v2.94h2.54V21h3.04z"/></svg>
          Facebook<span class="sr-only"> (nouvel onglet)</span>
        </a>
        <a href="{{ $partage['whatsapp'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full border border-line bg-white px-4 py-2.5 text-sm font-semibold text-ink transition duration-200 hover:border-primary hover:text-primary">
          <x-ui.icon-whatsapp class="size-4" />
          WhatsApp<span class="sr-only"> (nouvel onglet)</span>
        </a>
        <button type="button" @click="copier()" class="inline-flex items-center gap-2 rounded-full border border-line bg-white px-4 py-2.5 text-sm font-semibold text-ink transition duration-200 hover:border-primary hover:text-primary">
          <svg x-show="!copie" viewBox="0 0 24 24" class="size-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
          <svg x-show="copie" x-cloak viewBox="0 0 24 24" class="size-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.5 12.75l6 6 9-13.5"/></svg>
          <span x-text="copie ? 'Lien copié' : 'Copier le lien'">Copier le lien</span>
        </button>
        <span class="sr-only" role="status" x-text="copie ? 'Lien copié dans le presse-papiers' : ''"></span>
      </div>
    </div>

    <div class="mx-auto mt-10 flex max-w-[720px] flex-col items-start gap-6 sm:flex-row sm:items-center sm:justify-between">
      <a href="{{ route('blog') }}" class="inline-flex items-center gap-2 rounded font-semibold text-ink transition duration-200 hover:text-primary">
        <svg viewBox="0 0 24 24" class="size-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
        Tous les articles
      </a>
      <x-ui.button :href="route(config('site.form_route'))">Démarrer mon projet</x-ui.button>
    </div>
  </x-layout.section>

  {{-- Commentaires (publiés après relecture dans l'admin) --}}
  <x-layout.section bg="surface" id="commentaires">
    <div class="mx-auto max-w-[720px]">
      <h2 class="display text-[28px] md:text-[36px]">
        {{ $nbCommentaires > 0 ? $nbCommentaires.' '.$mot($nbCommentaires, 'commentaire', 'commentaires') : 'Commentaires' }}
      </h2>

      @if ($nbCommentaires > 0)
        <ul class="mt-8 flex flex-col gap-4">
          @foreach ($commentaires as $commentaire)
            <li class="rounded-[24px] border border-line bg-white p-5 md:p-6">
              <div class="flex items-center gap-3">
                <span class="grid size-10 shrink-0 place-items-center rounded-full bg-surface font-heading text-sm font-bold text-ink ring-1 ring-line" aria-hidden="true">{{ mb_strtoupper(mb_substr($commentaire->nom, 0, 1)) }}</span>
                <div class="leading-tight">
                  <p class="font-semibold text-ink">{{ $commentaire->nom }}</p>
                  <time datetime="{{ $commentaire->created_at->toDateString() }}" class="font-mono text-xs text-muted">{{ $commentaire->created_at->translatedFormat('j F Y') }}</time>
                </div>
              </div>
              <p class="mt-4 whitespace-pre-line text-base text-ink">{{ $commentaire->message }}</p>
            </li>
          @endforeach
        </ul>
      @else
        <p class="mt-4 text-base text-muted">Aucun commentaire pour l’instant. Une question, un avis, une expérience à partager&nbsp;? Soyez le premier.</p>
      @endif

      {{-- Formulaire --}}
      <div class="mt-10 rounded-[24px] border border-line bg-white p-6 shadow-card md:p-8">
        @if (session('commentaire_envoye'))
          <div class="flex items-start gap-3" role="status">
            <span class="grid size-10 shrink-0 place-items-center rounded-full bg-primary/10 text-primary" aria-hidden="true">
              <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 12.75l6 6 9-13.5"/></svg>
            </span>
            <div>
              <p class="font-heading text-lg font-bold text-ink">Merci, votre commentaire est bien arrivé.</p>
              <p class="mt-1 text-sm text-muted">Il sera publié après relecture.</p>
            </div>
          </div>
        @else
          <h3 class="display text-xl md:text-2xl">Laisser un commentaire</h3>
          <p class="mt-2 text-sm text-muted">Votre prénom et votre message suffisent. Les commentaires sont publiés après relecture (voir la <a href="{{ route('politique-confidentialite') }}" target="_blank" rel="noopener" class="font-medium text-ink underline decoration-primary underline-offset-2 hover:text-primary">politique de confidentialité</a>).</p>

          <form method="POST" action="{{ route('blog.commentaire', $article->slug) }}#commentaires" class="mt-6 grid gap-5" novalidate>
            @csrf
            <x-ui.field label="Prénom ou nom" name="nom" autocomplete="given-name" maxlength="80" required />
            <x-ui.field label="Votre commentaire" name="message" textarea maxlength="1500" required placeholder="Votre question, votre avis, votre expérience…" />
            {{-- Piège à robots (invisible) --}}
            <input type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" class="absolute -left-[9999px] h-0 w-0 opacity-0">
            <div>
              <x-ui.button type="submit">Publier mon commentaire</x-ui.button>
            </div>
          </form>
        @endif
      </div>
    </div>
  </x-layout.section>

  @if ($autres->isNotEmpty())
    <x-layout.section bg="white">
      <h2 class="display text-[28px] md:text-[36px]">À lire aussi</h2>
      <ul class="mt-8 grid gap-5 md:grid-cols-2 md:gap-6">
        @foreach ($autres as $autre)
          <li>
            <a href="{{ route('blog.article', $autre->slug) }}" class="group flex h-full flex-col rounded-[24px] border border-line bg-white p-6 shadow-card transition duration-300 ease-out hover:-translate-y-1 hover:border-primary/30 md:p-8">
              @if ($autre->categorie)
                <span class="mb-3 w-fit rounded-full bg-primary/10 px-2.5 py-0.5 font-mono text-[11px] font-medium uppercase tracking-[0.06em] text-primary">{{ $autre->categorie->nom }}</span>
              @endif
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
