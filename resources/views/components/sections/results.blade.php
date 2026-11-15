@php
$items = [
    ['Vous ne répétez plus.', 'Vos produits et vos services sont expliqués une fois, sur une seule page.',
        '<path d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>'],
    ['On vous comprend tout de suite.', 'Vos clients voient qui vous êtes et ce que vous vendez, sans fouiller dans vos messages.',
        '<path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'],
    ['On vous écrit en un clic.', 'Un bouton pour appeler, un bouton pour écrire sur WhatsApp. Aucun compte à créer.',
        '<path d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.834.166l-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243l-1.59-1.59"/>'],
];
@endphp

{{-- Titre affiche à gauche, puis une ligne par bénéfice : icône · titre · explication --}}
<x-layout.section bg="white">
  <div class="reveal" style="--i:0">
    <x-ui.section-heading align="left" display badge="Ce que vous y gagnez" title="Ce que votre site fait pour vous, chaque jour" />
  </div>

  <ul class="mt-12 border-b border-line">
    @foreach ($items as $i => [$title, $text, $icon])
      <li class="reveal group grid grid-cols-[auto_1fr] items-center gap-x-5 gap-y-3 border-t border-line py-7 md:grid-cols-[auto_1fr_1fr] md:gap-x-10 md:py-8" style="--i:{{ $i + 1 }}">
        <span class="grid size-14 place-items-center rounded-2xl bg-surface text-primary ring-1 ring-line transition duration-300 group-hover:bg-primary group-hover:text-white group-hover:ring-primary" aria-hidden="true">
          <svg viewBox="0 0 24 24" class="size-6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">{!! $icon !!}</svg>
        </span>
        <h3 class="display text-2xl md:text-[32px]">{{ $title }}</h3>
        <p class="col-span-2 text-base text-muted md:col-span-1 md:max-w-[420px] md:justify-self-end">{{ $text }}</p>
      </li>
    @endforeach
  </ul>
</x-layout.section>
