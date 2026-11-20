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
        text="Uniquement de vrais retours, avec l’accord de chaque client. Vous avez travaillé avec moi ? Votre avis peut être le premier."
      >
        <x-ui.button href="#laisser-un-avis" :icon="false">Laisser un avis</x-ui.button>
      </x-ui.empty-state>
    @endif
  </x-layout.section>

  {{-- Déposer un avis (publié après relecture dans l'admin) --}}
  <x-layout.section bg="surface" id="laisser-un-avis">
    <div class="mx-auto grid max-w-[1000px] gap-10 lg:grid-cols-[0.8fr_1.2fr] lg:gap-16">
      <div class="reveal" style="--i:0">
        <x-ui.section-heading align="left" display badge="Votre avis" title="Vous avez travaillé avec moi&nbsp;?" subtitle="Racontez simplement ce que votre site a changé pour vous, ce qui vous a plu, et ce qui pourrait être amélioré. Votre avis est publié après relecture." />
      </div>

      <div class="reveal rounded-[24px] border border-line bg-white p-6 shadow-card md:p-8" style="--i:1">
        @if (session('avis_envoye'))
          <div class="flex items-start gap-3 py-2" role="status">
            <span class="grid size-10 shrink-0 place-items-center rounded-full bg-primary/10 text-primary" aria-hidden="true">
              <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 12.75l6 6 9-13.5"/></svg>
            </span>
            <div>
              <p class="font-heading text-lg font-bold text-ink">Merci, votre avis est bien arrivé.</p>
              <p class="mt-1 text-sm text-muted">Il sera publié sur cette page après relecture.</p>
            </div>
          </div>
        @else
          <form method="POST" action="{{ route('avis.store') }}#laisser-un-avis" class="grid gap-5 md:grid-cols-2" novalidate>
            @csrf
            <x-ui.field label="Prénom ou nom" name="nom" autocomplete="name" maxlength="80" required />
            <x-ui.field label="Activité, ville (facultatif)" name="activite" maxlength="120" placeholder="Ex. Boutique de cosmétiques, Cotonou" />
            <x-ui.field label="Votre avis" name="contenu" textarea maxlength="1500" required class="md:col-span-2" placeholder="Votre expérience, ce que le site a changé pour votre activité…" />

            <div class="md:col-span-2">
              <label class="flex items-start gap-3 text-sm text-ink">
                <input type="checkbox" name="accord" value="1" @checked(old('accord')) class="mt-0.5 size-4 shrink-0 accent-primary" @error('accord') aria-invalid="true" @enderror>
                <span>J’accepte que cet avis soit publié sur ce site, avec mon nom et mon activité.</span>
              </label>
              @error('accord')
                <p class="mt-2 flex items-center gap-2 text-sm font-medium text-ink" role="alert">
                  <svg viewBox="0 0 20 20" class="size-4 shrink-0 text-primary" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.5a.75.75 0 00-1.5 0v4a.75.75 0 001.5 0v-4zM10 14.5a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                  {{ $message }}
                </p>
              @enderror
            </div>

            {{-- Piège à robots (invisible) --}}
            <input type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" class="absolute -left-[9999px] h-0 w-0 opacity-0">

            <div class="md:col-span-2">
              <x-ui.button type="submit">Envoyer mon avis</x-ui.button>
            </div>
          </form>
        @endif
      </div>
    </div>
  </x-layout.section>

  <x-sections.cta />
</x-layouts::app>
