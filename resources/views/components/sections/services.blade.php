@php
$services = [
    ['Site vitrine', 'Une présentation claire de votre activité, de vos offres et de vos tarifs, lisible en quelques secondes.',
        '<path d="M3 5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25v13.5A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 18.75V5.25zM3 8.25h18"/>'],
    ['Landing page', 'Une page unique, pensée pour une seule action : prendre contact, réserver ou acheter.',
        '<path d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.63 8.41m5.96 5.96a14.93 14.93 0 01-5.84 2.58m0 0a6 6 0 01-7.38-5.84h4.8m2.58-5.84a14.93 14.93 0 00-2.58 5.84"/>'],
    ['Application sur mesure', 'Espace client, back-office, réservations : un outil web adapté à votre façon de travailler.',
        '<path d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/>'],
];
@endphp

<x-layout.section bg="surface" id="services">
  <div class="reveal" style="--i:0" x-intersect.once="$el.classList.add('is-visible')">
    <x-ui.section-heading
      badge="Services"
      title="Ce que je peux construire pour vous"
      subtitle="Chaque projet part de vos clients : ce qu’ils cherchent, ce qui les rassure, ce qui les fait passer à l’action."
    />
  </div>

  <div class="mt-12 grid gap-5 md:grid-cols-3 md:gap-6">
    @foreach ($services as $i => [$title, $text, $icon])
      <div class="reveal" style="--i:{{ $i + 1 }}" x-intersect.once="$el.classList.add('is-visible')">
        <x-ui.card :title="$title" class="h-full">
          <x-slot:icon>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">{!! $icon !!}</svg>
          </x-slot:icon>
          {{ $text }}
        </x-ui.card>
      </div>
    @endforeach
  </div>
</x-layout.section>
