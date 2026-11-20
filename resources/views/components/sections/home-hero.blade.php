@php
$formRoute = config('site.form_route');
$formUrl = Route::has($formRoute) ? route($formRoute) : '#';
$telUrl = 'tel:'.preg_replace('/[^0-9+]/', '', (string) config('site.phone'));
$waUrl = 'https://wa.me/'.preg_replace('/[^0-9]/', '', (string) config('site.whatsapp'))
    .'?text='.urlencode((string) config('site.whatsapp_message'));

$photo = 'images/about/primous.jpg';
$hasPhoto = file_exists(public_path($photo));

// Rangée du bas (à la place de chiffres inventés) : les garanties de l'offre, tirées des textes validés.
$promises = ['Cinq questions, à votre rythme', 'Une proposition adaptée', 'Premier échange gratuit'];
@endphp

<section class="bg-white md:px-5 md:pt-5">
  {{-- Grand panneau noir arrondi, en pleine largeur --}}
  <div class="relative isolate mx-auto max-w-[1440px] overflow-hidden rounded-b-[28px] bg-ink-soft text-white md:rounded-[36px]">
    {{-- Décor : grille très fine + halo violet (8 % max) --}}
    <div class="pointer-events-none absolute inset-0 -z-10 opacity-[0.06] [background-image:linear-gradient(to_right,#fff_1px,transparent_1px),linear-gradient(to_bottom,#fff_1px,transparent_1px)] [background-size:64px_64px]" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -right-40 -top-40 -z-10 size-[720px] rounded-full bg-[radial-gradient(circle,color-mix(in_oklab,var(--color-primary)_8%,transparent)_0%,transparent_70%)]" aria-hidden="true"></div>

    <div class="mx-auto grid min-h-[calc(100svh-72px)] w-full max-w-[1300px] content-center items-end gap-10 px-6 pt-14 md:min-h-0 md:content-normal md:px-12 md:pt-20 lg:grid-cols-[1.25fr_0.75fr] lg:gap-6">
      {{-- Texte --}}
      <div class="flex flex-col items-center pb-14 text-center md:items-start md:pb-4 md:text-left lg:pb-16">
        <div class="reveal" style="--i:0">
          <x-ui.badge class="!border-white/15 !bg-white/10 !text-white !px-4 !py-1.5 !leading-relaxed md:!px-3 md:!py-1">Sites internet pour commerçants, artisans et indépendants à Cotonou</x-ui.badge>
        </div>

        <h1 class="reveal display mt-9 text-[40px] leading-[1.08] text-white sm:text-[52px] md:mt-6 md:text-[64px] md:leading-none lg:text-[72px]" style="--i:1">
          Expliquez votre activité <span class="marker-strong">une seule fois</span>.
        </h1>

        <p class="reveal mx-auto mt-7 max-w-[310px] text-base leading-[1.75] text-white/70 sm:max-w-[440px] md:mx-0 md:mt-6 md:max-w-[520px] md:text-lg md:leading-[1.6]" style="--i:2">
          Le client comprend votre offre sur votre site, puis vous écrit prêt à acheter. Plus besoin de répéter les mêmes réponses.
        </p>

        <div class="reveal mt-11 flex flex-col items-center gap-4 md:mt-8 md:items-start" style="--i:3">
          <div class="flex flex-col flex-wrap items-center justify-center gap-x-6 gap-y-7 sm:flex-row md:justify-start md:gap-y-4">
            <x-ui.button :href="$formUrl">Démarrer mon projet</x-ui.button>
            <p class="flex items-center gap-3 text-sm font-semibold text-white">
              <a href="{{ $telUrl }}" class="inline-flex items-center gap-1.5 rounded transition duration-200 hover:text-white/70">
                <svg viewBox="0 0 24 24" class="size-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                </svg>
                Appeler
              </a>
              <span class="text-white/40" aria-hidden="true">·</span>
              <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 rounded transition duration-200 hover:text-white/70">
                <x-ui.icon-whatsapp class="size-4 shrink-0" />
                WhatsApp
              </a>
            </p>
          </div>
        </div>
      </div>

      {{-- Portrait, fondu dans le panneau --}}
      @if ($hasPhoto)
        <div class="reveal-scale relative mx-auto hidden w-full max-w-[460px] md:block lg:mx-0 lg:max-w-none" style="--i:0">
          <img src="{{ asset($photo) }}" alt="Hounkpatin Primous, créateur de sites internet à Cotonou" width="941" height="941" loading="eager" fetchpriority="high" decoding="async"
               class="block h-auto w-full [mask-image:radial-gradient(ellipse_50%_58%_at_50%_42%,#000_50%,transparent_100%)]">
        </div>
      @endif
    </div>

    {{-- Rangée du bas : les trois promesses, séparées par des filets --}}
    <ul class="mx-auto hidden w-full max-w-[1300px] border-t border-white/15 px-6 md:grid md:grid-cols-3 md:px-12">
      @foreach ($promises as $n => $promise)
        <li @class(['reveal flex items-center justify-center gap-4 py-6 md:justify-start md:py-8', 'border-t border-white/15 md:border-t-0' => $n > 0, 'md:border-l md:border-white/15 md:pl-8' => $n > 0]) style="--i:{{ $n + 1 }}">
          <svg viewBox="0 0 24 24" class="size-7 shrink-0" aria-hidden="true"><circle cx="12" cy="12" r="12" class="fill-primary"/><path d="M7.5 12.5l3 3 6-6.5" fill="none" class="stroke-white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <span class="display text-lg text-white md:text-xl">{{ $promise }}</span>
        </li>
      @endforeach
    </ul>
  </div>
</section>
