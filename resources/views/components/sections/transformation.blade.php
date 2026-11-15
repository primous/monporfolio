@php
// Conversation « avant » : les mêmes questions, encore et encore. 'in' = client, 'out' = vous.
$bubbles = [
    ['in', 'Bonjour, c’est combien ?', '08:12'],
    ['in', 'Vous livrez où ?', '09:40'],
    ['out', 'Je vous envoie les photos tout de suite.', '09:41'],
    ['in', 'C’est encore disponible ?', '13:05'],
    ['in', 'Bonjour, c’est combien ?', '18:27'],
];
$rows = ['Vos prix, clairement affichés', 'Vos zones de livraison', 'Les photos de vos produits'];
$nbsp = fn (string $t) => str_replace([' ?', ' :'], ["\u{00A0}?", "\u{00A0}:"], $t);
@endphp

<x-layout.section bg="white">
  <div class="grid items-center gap-14 lg:grid-cols-2 lg:gap-16">
    <div class="flex flex-col items-start gap-6">
      <div class="reveal" style="--i:0">
        <x-ui.section-heading align="left" display badge="La transformation" title="Soyez l’entrepreneur dont l’activité se comprend d’un coup d’œil" />
      </div>

      <p class="reveal text-base text-ink md:text-lg" style="--i:1">
        Un site clair donne confiance dès la première seconde. Vos clients voient le sérieux de ce que vous faites, et vous gagnez du temps pour créer, vendre et communiquer.
      </p>

      {{-- Ce qui distingue l'offre : l'accompagnement après la mise en ligne --}}
      <div class="reveal flex w-full items-start gap-4 rounded-[24px] border border-line bg-surface p-5 md:p-6" style="--i:2">
        <span class="grid size-12 shrink-0 place-items-center rounded-xl bg-primary/10 text-primary" aria-hidden="true">
          <svg viewBox="0 0 24 24" class="size-6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/>
          </svg>
        </span>
        <div>
          <h3 class="display text-xl">Et après la mise en ligne&nbsp;?</h3>
          <p class="mt-1 text-base text-muted">Je ne vous laisse pas seul&nbsp;: je vous aide à faire venir des visiteurs sur votre site.</p>
        </div>
      </div>
    </div>

    {{-- Visuel signature : la conversation répétitive devient une page claire (joué une fois) --}}
    <div class="reveal convo relative mx-auto w-full max-w-[520px] pb-40 sm:pb-44" style="--i:1">
      <p class="sr-only">Illustration : des messages de clients qui posent toujours les mêmes questions deviennent une page claire avec les prix, la livraison et les photos.</p>

      <div class="chat-card w-[88%] rounded-[24px] border border-line bg-white p-4 shadow-card sm:p-5" aria-hidden="true">
        <div class="flex items-center justify-between border-b border-line pb-3">
          <div class="flex items-center gap-3">
            <span class="grid size-9 place-items-center rounded-full bg-surface font-heading text-sm font-bold text-ink">C</span>
            <div class="leading-tight">
              <p class="text-sm font-semibold text-ink">Vos clients</p>
              <p class="font-mono text-[11px] text-muted">Messagerie</p>
            </div>
          </div>
          <span class="rounded-full border border-line bg-surface px-2.5 py-0.5 font-mono text-[11px] font-medium uppercase tracking-wide text-ink">Avant</span>
        </div>
        <ul class="mt-4 flex flex-col gap-2.5">
          @foreach ($bubbles as $b => [$side, $text, $time])
            <li class="bubble flex {{ $side === 'out' ? 'justify-end' : 'justify-start' }}" style="--b:{{ $b }}">
              <span @class([
                'max-w-[80%] rounded-2xl px-3.5 py-2 text-[13px] leading-snug',
                'rounded-bl-md bg-surface text-ink' => $side === 'in',
                'rounded-br-md bg-ink text-white' => $side === 'out',
              ])>
                {{ $nbsp($text) }}
                <span @class(['ml-2 align-bottom font-mono text-[10px]', 'text-muted' => $side === 'in', 'text-white/60' => $side === 'out'])>{{ $time }}</span>
              </span>
            </li>
          @endforeach
        </ul>
      </div>

      <div class="site-card absolute bottom-0 right-0 w-[72%] overflow-hidden rounded-[24px] border border-line bg-white shadow-float" aria-hidden="true">
        <div class="flex items-center gap-3 border-b border-line bg-surface px-4 py-2.5">
          <span class="flex gap-1.5"><span class="size-2 rounded-full bg-line"></span><span class="size-2 rounded-full bg-line"></span><span class="size-2 rounded-full bg-line"></span></span>
          <span class="truncate rounded-full bg-white px-3 py-0.5 font-mono text-[11px] text-muted ring-1 ring-line">votre-activite.com</span>
        </div>
        <div class="p-4 sm:p-5">
          <div class="flex items-center justify-between gap-3">
            <p class="font-heading text-base font-bold tracking-tight text-ink sm:text-lg">Votre activité</p>
            <span class="rounded-full bg-primary/10 px-2.5 py-0.5 font-mono text-[11px] font-medium uppercase tracking-wide text-primary">Après</span>
          </div>
          <ul class="mt-3 flex flex-col gap-2">
            @foreach ($rows as $row)
              <li class="flex items-center gap-2.5 text-[13px] font-medium text-ink">
                <svg viewBox="0 0 24 24" class="size-4 shrink-0" aria-hidden="true"><circle cx="12" cy="12" r="12" class="fill-primary"/><path d="M7.5 12.5l3 3 6-6.5" fill="none" class="stroke-white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                {{ $row }}
              </li>
            @endforeach
          </ul>
          <span class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-full bg-primary px-4 py-2 text-[13px] font-semibold text-white">
            <x-ui.icon-whatsapp class="size-4" />
            Commander en un clic
          </span>
        </div>
      </div>

    </div>
  </div>
</x-layout.section>
