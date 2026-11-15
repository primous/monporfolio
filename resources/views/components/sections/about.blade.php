@php
$formRoute = config('site.form_route');
$formUrl = Route::has($formRoute) ? route($formRoute) : '#';

$photo = 'images/about/primous.jpg';
$hasPhoto = file_exists(public_path($photo));

// Points forts (colonne de droite) : ma façon de travailler, en une ligne chacun.
$points = [
    ['Je comprends votre activité', '<path d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>'],
    ['Je clarifie vos offres et vos prix', '<path d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>'],
    ['Je construis un site rapide et mobile', '<path d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/>'],
    ['Je vous accompagne après la mise en ligne', '<path d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/>'],
];
@endphp

{{-- Bannière horizontale : portrait · présentation · points forts --}}
<x-layout.section bg="white" id="parcours">
  <div class="reveal relative isolate overflow-hidden rounded-[28px] bg-ink-soft p-4 text-white md:p-5" style="--i:0">
    {{-- Décor discret : halo rouge (8 % max) --}}
    <div class="pointer-events-none absolute -right-32 -top-32 -z-10 size-[520px] rounded-full bg-[radial-gradient(circle,color-mix(in_oklab,var(--color-primary)_8%,transparent)_0%,transparent_70%)]" aria-hidden="true"></div>

    <div class="grid items-center gap-8 md:grid-cols-[0.85fr_1.15fr] lg:grid-cols-[0.8fr_1.25fr_1fr] lg:gap-10">
      {{-- Portrait --}}
      @if ($hasPhoto)
        <div class="relative mx-auto w-full max-w-[360px] overflow-hidden rounded-[22px] ring-1 ring-white/10 md:max-w-none md:self-stretch">
          <img src="{{ asset($photo) }}" alt="Portrait de Hounkpatin Primous" width="941" height="941" loading="lazy"
               class="block aspect-square h-full w-full object-cover object-top md:aspect-auto md:min-h-[320px]">
        </div>
      @endif

      {{-- Présentation --}}
      <div class="flex flex-col items-center px-2 py-2 text-center md:items-start md:px-0 md:py-6 md:text-left">
        <x-ui.badge class="!border-white/15 !bg-white/10 !text-white">Qui est derrière</x-ui.badge>
        <h2 class="display mt-5 text-[30px] text-white md:text-[36px] lg:text-[40px]">Je fais pour vous ce que j’ai fait pour moi</h2>
        <p class="mt-4 max-w-[520px] text-base text-white/70">
          Je suis Hounkpatin Primous, créateur de sites internet. Le site que vous lisez est le mien&nbsp;: il présente mes services, reçoit les demandes et garde tout au même endroit. C’est cette clarté que je construis pour vous.
        </p>
        <div class="mt-7">
          <x-ui.button :href="$formUrl">Démarrer mon projet</x-ui.button>
        </div>
      </div>

      {{-- Points forts --}}
      <ul class="grid gap-x-6 border-t border-white/10 px-2 pt-6 sm:grid-cols-2 md:col-span-2 lg:col-span-1 lg:grid-cols-1 lg:border-l lg:border-t-0 lg:py-4 lg:pl-8 lg:pr-4 lg:pt-4">
        @foreach ($points as $n => [$label, $icon])
          <li @class(['flex items-center gap-4 py-4', 'border-t border-white/10' => $n > 0, 'sm:border-t-0 lg:border-t' => $n === 1, 'sm:border-t lg:border-t' => $n > 1])>
            <span class="grid size-11 shrink-0 place-items-center rounded-full bg-white/10 text-white ring-1 ring-white/15" aria-hidden="true">
              <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">{!! $icon !!}</svg>
            </span>
            <span class="font-heading text-base font-semibold text-white">{{ $label }}</span>
          </li>
        @endforeach
      </ul>
    </div>
  </div>
</x-layout.section>
