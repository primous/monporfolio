@php
// Projets publiés dans l'admin (Contenu du site → Projets), avec un visuel présent sur le disque.
$projects = \App\Models\Projet::visibles()->get()
    ->map(function ($p) {
        $dims = $p->visuelDimensions();

        return $dims ? [
            'name' => $p->titre,
            'description' => $p->resume ?: $p->description,
            'src' => $p->visuelUrl(),
            'width' => $dims[0],
            'height' => $dims[1],
        ] : null;
    })
    ->filter()
    ->values();
$count = $projects->count();

// Pastille d'icône par projet (trait blanc sur carré rouge). Repli : fenêtre de navigateur.
$icons = [
    'Evenzia' => '<path d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>',
    'Petits Pas' => '<path d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z"/>',
    'Talent Club' => '<path d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.990 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"/>',
    'AnonyVox' => '<path d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/>',
    'Vécu' => '<path d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>',
    'Trouvez votre évidence' => '<path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.780 9-12z"/>',
];
$fallbackIcon = '<path d="M3 5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25v13.5A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 18.750V5.25zM3 8.25h18"/>';

// Position initiale (avant Alpine / sans JavaScript) : carte 0 au centre, voisines de part et d'autre.
$offset = function (int $i) use ($count) {
    $o = $i;
    if ($o > $count / 2) $o -= $count;
    return $o;
};
@endphp

@if ($count > 0)
{{-- Carrousel 3D : la carte active au centre, les voisines inclinées vers elle --}}
<section id="projets" class="overflow-hidden bg-ink-soft py-16 text-white md:py-24"
  x-data="{
    active: 0,
    count: {{ $count }},
    cardW: 300,
    visible: 2,
    startX: null,
    swiped: false,
    measure() {
      const w = this.$refs.stage.clientWidth;
      this.cardW = w < 640 ? Math.min(260, Math.round(w * 0.68)) : (w < 1024 ? 270 : 300);
      this.visible = w < 640 ? 1 : 2;
    },
    offset(i) {
      let o = i - this.active;
      if (o > this.count / 2) o -= this.count;
      if (o < -this.count / 2) o += this.count;
      return o;
    },
    card(i) {
      const o = this.offset(i), a = Math.abs(o), dir = o === 0 ? 0 : (o > 0 ? -1 : 1);
      const x = o * (this.cardW * 1.02) - Math.sign(o) * Math.max(0, a - 1) * this.cardW * 0.08;
      const hidden = a > this.visible;
      return {
        width: this.cardW + 'px',
        marginLeft: -(this.cardW / 2) + 'px',
        transform: `translateX(${x}px) translateZ(${-a * 90}px) rotateY(${dir * Math.min(a * 22, 40)}deg)`,
        zIndex: 20 - a,
        opacity: hidden ? 0 : (a === 0 ? 1 : 0.92),
        pointerEvents: hidden ? 'none' : 'auto',
      };
    },
    go(i) { this.active = (i + this.count) % this.count; },
    next() { this.go(this.active + 1); },
    prev() { this.go(this.active - 1); },
    down(e) { this.startX = e.clientX; this.swiped = false; },
    up(e) { if (this.startX === null) return; const dx = e.clientX - this.startX; this.startX = null; if (Math.abs(dx) > 40) { this.swiped = true; dx < 0 ? this.next() : this.prev(); } },
  }"
  x-init="measure()"
  @resize.window.debounce.100ms="measure()"
>
  <div class="mx-auto w-full max-w-[1200px] px-6 text-center md:px-12">
    <div class="reveal" style="--i:0">
      <x-ui.section-heading dark display badge="Réalisations" title="Voyez par vous-même" subtitle="Quelques projets que j’ai conçus et développés." />
    </div>
  </div>

  {{-- Scène 3D --}}
  <div class="reveal relative mt-12 md:mt-16" style="--i:1">
    <div
      x-ref="stage"
      class="relative mx-auto h-[380px] w-full select-none outline-none [perspective:1400px] sm:h-[400px] lg:h-[440px]"
      role="region" aria-roledescription="carrousel" aria-label="Réalisations" tabindex="0"
      @keydown.arrow-right.prevent="next()" @keydown.arrow-left.prevent="prev()"
      @pointerdown="down($event)" @pointerup="up($event)" @pointercancel="startX = null"
    >
      <ul class="absolute inset-0 [transform-style:preserve-3d]">
        @foreach ($projects as $i => $project)
          @php $o = $offset($i); $a = abs($o); @endphp
          <li
            class="absolute left-1/2 top-0 transition-[transform,opacity] duration-700 ease-out motion-reduce:transition-none"
            style="width:300px; margin-left:-150px; transform:translateX({{ $o * 306 - ($o <=> 0) * max(0, $a - 1) * 24 }}px) translateZ({{ -$a * 90 }}px) rotateY({{ $o === 0 ? 0 : ($o > 0 ? -1 : 1) * min($a * 22, 40) }}deg); z-index:{{ 20 - $a }}; opacity:{{ $a > 2 ? 0 : 1 }}"
            :style="card({{ $i }})"
            aria-roledescription="diapositive"
            aria-label="{{ $i + 1 }} sur {{ $count }} : {{ $project['name'] }}"
            :aria-hidden="(Math.abs(offset({{ $i }})) > visible).toString()"
          >
            <article
              class="group relative aspect-[3/4] w-full cursor-pointer overflow-hidden rounded-[22px] bg-ink shadow-float ring-1 ring-white/10 transition duration-300"
              :class="active === {{ $i }} ? 'ring-white/25' : 'hover:ring-white/30'"
              @click="!swiped && active !== {{ $i }} && go({{ $i }})"
            >
              {{-- Capture en pleine carte, cadrée sur le haut de la page (logo et titre du site) --}}
              <img src="{{ $project['src'] }}" alt="Aperçu du projet {{ $project['name'] }}"
                   width="{{ $project['width'] }}" height="{{ $project['height'] }}" loading="lazy" decoding="async" draggable="false"
                   class="absolute inset-0 h-full w-full object-cover object-left-top transition duration-700 ease-out group-hover:scale-[1.03]">

              {{-- Voile sombre en bas, pour la lisibilité du texte --}}
              <div class="absolute inset-x-0 bottom-0 h-[55%] bg-gradient-to-t from-black/95 via-black/65 to-transparent" aria-hidden="true"></div>

              {{-- Pastille d'icône --}}
              <span class="absolute right-4 top-4 grid size-10 place-items-center rounded-xl bg-primary text-white shadow-card" aria-hidden="true">
                <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">{!! $icons[$project['name']] ?? $fallbackIcon !!}</svg>
              </span>

              <div class="absolute inset-x-0 bottom-0 p-5 md:p-6">
                <span class="font-mono text-xs font-medium tracking-wide text-white/50">{{ sprintf('%02d', $i + 1) }}</span>
                <h3 class="display mt-1 text-2xl text-white">{{ $project['name'] }}</h3>
                <p class="mt-2 text-sm text-white/75">{{ $project['description'] }}</p>
              </div>
            </article>
          </li>
        @endforeach
      </ul>
    </div>
  </div>

  {{-- Contrôles : flèches + points --}}
  <div class="reveal mt-10 flex items-center justify-center gap-6" style="--i:2">
    <button type="button" @click="prev()" aria-label="Projet précédent"
      class="grid size-12 place-items-center rounded-full border border-white/20 text-white transition duration-200 hover:border-primary hover:bg-primary">
      <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
    </button>

    <div class="flex items-center gap-2">
      @foreach ($projects as $i => $project)
        <button type="button" @click="go({{ $i }})"
          class="h-2 rounded-full transition-all duration-300"
          :class="active === {{ $i }} ? 'w-6 bg-primary' : 'w-2 bg-white/30 hover:bg-white/60'"
          :aria-current="active === {{ $i }} ? 'true' : 'false'"
          aria-label="Voir le projet {{ $project['name'] }}"></button>
      @endforeach
    </div>

    <button type="button" @click="next()" aria-label="Projet suivant"
      class="grid size-12 place-items-center rounded-full border border-white/20 text-white transition duration-200 hover:border-primary hover:bg-primary">
      <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
    </button>
  </div>

  {{-- Vers la page Projets --}}
  <div class="reveal mt-10 flex justify-center px-6" style="--i:3">
    <a href="{{ route('projets') }}" class="inline-flex items-center gap-2 rounded font-semibold text-white underline decoration-primary decoration-2 underline-offset-4 transition duration-200 hover:text-white/80">
      Voir tous les projets
      <svg viewBox="0 0 24 24" class="size-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </a>
  </div>
</section>
@endif
