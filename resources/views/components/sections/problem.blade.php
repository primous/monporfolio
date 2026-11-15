@php
$formRoute = config('site.form_route');
$formUrl = Route::has($formRoute) ? route($formRoute) : '#';

// Une journée type : matin, après-midi, soir.
$moments = [
    ['Le matin', 'Les mêmes questions.', '<path d="M12 3v1.5M5.6 5.6l1.06 1.06M3 12h1.5M18.34 6.66 19.4 5.6M19.5 12H21M7.5 15.75a4.5 4.5 0 119 0M3 18.75h18"/>'],
    ['L’après-midi', 'Les mêmes photos, envoyées une à une.', '<path d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>'],
    ['Le soir', 'Les commandes, à noter à la main.', '<path d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/>'],
];
@endphp

<x-layout.section bg="surface">
  {{-- Grande déclaration centrée --}}
  <h2 class="reveal display mx-auto max-w-[980px] text-center text-[34px] md:text-[52px] lg:text-[64px]" style="--i:0">
    Vous méritez mieux que les mêmes réponses, tous les jours
  </h2>

  {{-- Filet vertical, puis la chute --}}
  <div class="reveal mx-auto mt-10 flex max-w-[640px] flex-col items-center gap-6 text-center" style="--i:1">
    <span class="block h-16 w-px bg-ink/30" aria-hidden="true"></span>
    <p class="text-lg font-semibold text-ink md:text-xl">
      Ce n’est pas un manque d’efforts de votre part&nbsp;: il vous manque une page qui parle à votre place.
    </p>
  </div>

  {{-- Une journée, en trois cartes numérotées --}}
  <ol class="mt-12 grid gap-4 md:grid-cols-3 md:gap-0 md:divide-x md:divide-line md:rounded-[24px] md:border md:border-line md:bg-white">
    @foreach ($moments as $n => [$label, $text, $icon])
      <li class="reveal flex flex-col gap-5 rounded-[24px] border border-line bg-white p-6 md:rounded-none md:border-0 md:p-8" style="--i:{{ $n + 1 }}">
        <div class="flex items-start justify-between">
          <span class="grid size-11 place-items-center rounded-xl bg-primary/10 text-primary" aria-hidden="true">
            <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">{!! $icon !!}</svg>
          </span>
          <span class="font-mono text-sm font-medium text-muted" aria-hidden="true">{{ sprintf('%02d', $n + 1) }}</span>
        </div>
        <p class="display text-2xl text-ink">{{ $label }}</p>
        <p class="-mt-2 text-base text-muted">{{ $text }}</p>
      </li>
    @endforeach
  </ol>

  <div class="reveal mt-12 flex justify-center" style="--i:3">
    <x-ui.button :href="$formUrl">Je veux ma page</x-ui.button>
  </div>
</x-layout.section>
